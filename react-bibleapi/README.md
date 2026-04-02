# React Bible API

This directory contains a separate React-based Bible reader that consumes the existing PHP Bible JSON endpoint.

Files:

- `index.html`: standalone entry page
- `app.js`: React UI and API calls
- `styles.css`: component styling

API behavior:

- First tries `/api/bible/{book}/{chapter}?version=...`
- Falls back to `/bibleapi.php?__route__=/api/bible/{book}/{chapter}&version=...`

Open `react-bibleapi/index.html` from your web server to use it.
