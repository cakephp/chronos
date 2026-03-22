import baseConfig from '@cakephp/docs-skeleton/config'
import { createRequire } from 'module'

const require = createRequire(import.meta.url)
const toc_en = require('./toc_en.json')
const toc_fr = require('./toc_fr.json')
const toc_ja = require('./toc_ja.json')
const toc_pt = require('./toc_pt.json')

const versions = {
  text: '3.x',
  items: [
    { text: '3.x (current)', link: 'https://book.cakephp.org/chronos/3/en/', target: '_self' },
    { text: '2.x', link: 'https://book.cakephp.org/chronos/2/en/', target: '_self' },
  ],
}

export default {
  extends: baseConfig,
  srcDir: '.',
  title: 'Chronos',
  description: 'CakePHP Chronos Documentation',
  base: '/chronos/3/',
  rewrites: {
    'en/:slug*': ':slug*',
  },
  sitemap: {
    hostname: 'https://book.cakephp.org/chronos/3/',
  },
  themeConfig: {
    socialLinks: [
      { icon: 'github', link: 'https://github.com/cakephp/chronos' },
    ],
    editLink: {
      pattern: 'https://github.com/cakephp/chronos/edit/3.x/docs/:path',
      text: 'Edit this page on GitHub',
    },
    sidebar: toc_en,
    nav: [
      { text: 'CakePHP', link: 'https://cakephp.org' },
      { text: 'API', link: 'https://api.cakephp.org/chronos' },
      { ...versions },
    ],
  },
  locales: {
    root: {
      label: 'English',
      lang: 'en',
      themeConfig: {
        sidebar: toc_en,
      },
    },
    fr: {
      label: 'Français',
      lang: 'fr',
      themeConfig: {
        sidebar: toc_fr,
      },
    },
    ja: {
      label: '日本語',
      lang: 'ja',
      themeConfig: {
        sidebar: toc_ja,
      },
    },
    pt: {
      label: 'Português',
      lang: 'pt',
      themeConfig: {
        sidebar: toc_pt,
      },
    },
  },
}
