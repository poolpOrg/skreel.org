# skreel.org

## pre-requisites

- python3
- pelican


## build website

Fetch latest copy of pelican theme for skreel.org and enter the directory:

    $ git clone https://github.com/poolpOrg/skreel.org
    $ cd skreel.org

Fetch latest copy of posts for skreel.org:

    $ git clone https://github.com/poolpOrg/skreel.org_posts

Rebuild website in subdirectory `output`:

    $ pelican -t theme -o output skreel.org_posts/content
