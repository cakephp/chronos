# ----------------------
# 1. Build stage
# ----------------------
FROM node:22-alpine AS builder

# Git is required because docs/package.json pulls a dependency from GitHub.
RUN apk add --no-cache git openssh-client

WORKDIR /app/docs

# Copy dependency manifests first to preserve Docker layer caching.
COPY docs/ ./
RUN npm ci

# Increase max-old-space-size to avoid memory issues during build
ENV NODE_OPTIONS="--max-old-space-size=8192"

# Build the site.
RUN npm run docs:build

# ----------------------
# 2. Runtime stage (nginx)
# ----------------------
FROM nginx:1.27-alpine AS runner

# Copy built files
COPY --from=builder /app/docs/.vitepress/dist /usr/share/nginx/html

# Expose port
EXPOSE 80

# Health check (optional)
HEALTHCHECK CMD wget --quiet --tries=1 --spider http://localhost:80/ || exit 1

# Start nginx
CMD ["nginx", "-g", "daemon off;"]
