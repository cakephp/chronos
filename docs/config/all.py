# Global configuration information used across all the
# translations of documentation.
#
# Import the base theme configuration
from cakephpsphinx.config.all import *

# The version info for the project you're documenting, acts as replacement for
# |version| and |release|, also used in various other places throughout the
# built documents.
#

# The full version, including alpha/beta/rc tags.
release = '1.x'

# The search index version.
search_version = 'chronos-1'

# The marketing display name for the book.
version_name = ''

# Project name shown in the black header bar
project = 'Chronos'

# Other versions that display in the version picker menu.
version_list = [
    {'name': '1.x', 'number': '/chronos/1.x', 'title': '1.x', 'current': True},
]

# Languages available.
languages = ['en', 'fr', 'ja', 'pt']

# The GitHub branch name for this version of the docs
# for edit links to point at.
branch = 'master'

# Current version being built
version = '1.x'

# Language in use for this directory.
language = 'en'

show_root_link = True

repository = 'cakephp/chronos'

source_path = 'docs/'
