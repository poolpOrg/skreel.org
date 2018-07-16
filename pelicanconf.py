#!/usr/bin/env python
# -*- coding: utf-8 -*- #
from __future__ import unicode_literals

AUTHOR = 'gilles'
SITENAME = 'skreel.org'
SITEURL = 'https://skreel.org'
SLUGIFY_SOURCE = 'title'
TYPOGRIFY = True

PATH = 'content'

TIMEZONE = 'Europe/Paris'

DEFAULT_LANG = 'en'
DEFAULT_CATEGORY = 'misc'
DEFAULT_DATE_FORMAT = '%Y-%m-%d'

# Feed generation is usually not desired when developing
FEED_ALL_ATOM = None
CATEGORY_FEED_ATOM = None
TRANSLATION_FEED_ATOM = None
AUTHOR_FEED_ATOM = None
AUTHOR_FEED_RSS = None

FEED_ALL_ATOM = 'feeds/all.atom.xml'
FEED_ALL_RSS = 'feeds/all.rss.xml'

CATEGORY_FEED_ATOM = 'feeds/category-%s.atom.xml'
CATEGORY_FEED_RSS = 'feeds/category-%s.rss.xml'

AUTHOR_FEED_ATOM = 'feeds/author-%s.atom.xml'
AUTHOR_FEED_RSS = 'feeds/author-%s.rss.xml'

TAG_FEED_ATOM = 'feeds/tag-%s.atom.xml'
TAG_FEED_RSS = 'feeds/tag-%s.rss.xml'


# Blogroll
LINKS = None

# Social widget
SOCIAL = None

DEFAULT_PAGINATION = 5

# Uncomment following line if you want document-relative URLs when developing
RELATIVE_URLS = False

PYGMENTS_RST_OPTIONS = {'linenos': 'table'}
PLUGINS = [
]

ARTICLE_URL = 'posts/{date:%Y}-{date:%m}-{date:%d}/{slug}/'
ARTICLE_SAVE_AS = 'posts/{date:%Y}-{date:%m}-{date:%d}/{slug}/index.html'

YEAR_ARCHIVE_SAVE_AS = 'posts/{date:%Y}/index.html'
MONTH_ARCHIVE_SAVE_AS = 'posts/{date:%Y}-{date:%b}/index.html'


TAG_CLOUD_STEPS = 4
TAG_CLOUD_MAX_ITEMS = 100
