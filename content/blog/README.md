# Blog post packages

Each folder holds one IndigeneTech blog post, ready to publish in WordPress. Posts are WordPress content (database), not theme code, so they are not deployed by merging this branch.

| Folder | Post | Status |
|---|---|---|
| `erp-for-economic-development-corporations/` | ERP for Economic Development Corporations: One System or Several? | Ready; created on staging for review |
| `data-governance-questions-technology-vendor/` | Data Governance Questions to Ask a Technology Vendor: A Guide for First Nations Organizations | Ready; created on staging for review |

## Publishing a post on live

1. Posts → Add New. Title = the H1 in `publish-notes.md` (section B).
2. Switch the editor to the **Text** tab and paste `post.html` exactly as is (one element per line; don't reformat).
3. Permalink (slug), Category **Business**, Rank Math focus keyword, SEO title and meta description: from `publish-notes.md` section B.
4. Add a featured image matching the concept and alt text in section B.
5. Preview: check the table of contents (left sidebar) and the FAQ section at the end, then publish.

Alternatively, export the reviewed posts from staging (Tools → Export → Posts) and import them on live.
