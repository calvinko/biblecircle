const { useEffect, useMemo, useState } = React;

const BOOKS = [
  { id: 1, name: "Genesis", chapters: 50 },
  { id: 2, name: "Exodus", chapters: 40 },
  { id: 3, name: "Leviticus", chapters: 27 },
  { id: 4, name: "Numbers", chapters: 36 },
  { id: 5, name: "Deuteronomy", chapters: 34 },
  { id: 6, name: "Joshua", chapters: 24 },
  { id: 7, name: "Judges", chapters: 21 },
  { id: 8, name: "Ruth", chapters: 4 },
  { id: 9, name: "1 Samuel", chapters: 31 },
  { id: 10, name: "2 Samuel", chapters: 24 },
  { id: 11, name: "1 Kings", chapters: 22 },
  { id: 12, name: "2 Kings", chapters: 25 },
  { id: 13, name: "1 Chronicles", chapters: 29 },
  { id: 14, name: "2 Chronicles", chapters: 36 },
  { id: 15, name: "Ezra", chapters: 10 },
  { id: 16, name: "Nehemiah", chapters: 13 },
  { id: 17, name: "Esther", chapters: 10 },
  { id: 18, name: "Job", chapters: 42 },
  { id: 19, name: "Psalms", chapters: 150 },
  { id: 20, name: "Proverbs", chapters: 31 },
  { id: 21, name: "Ecclesiastes", chapters: 12 },
  { id: 22, name: "Song of Solomon", chapters: 8 },
  { id: 23, name: "Isaiah", chapters: 66 },
  { id: 24, name: "Jeremiah", chapters: 52 },
  { id: 25, name: "Lamentations", chapters: 5 },
  { id: 26, name: "Ezekiel", chapters: 48 },
  { id: 27, name: "Daniel", chapters: 12 },
  { id: 28, name: "Hosea", chapters: 14 },
  { id: 29, name: "Joel", chapters: 3 },
  { id: 30, name: "Amos", chapters: 9 },
  { id: 31, name: "Obadiah", chapters: 1 },
  { id: 32, name: "Jonah", chapters: 4 },
  { id: 33, name: "Micah", chapters: 7 },
  { id: 34, name: "Nahum", chapters: 3 },
  { id: 35, name: "Habakkuk", chapters: 3 },
  { id: 36, name: "Zephaniah", chapters: 3 },
  { id: 37, name: "Haggai", chapters: 2 },
  { id: 38, name: "Zechariah", chapters: 14 },
  { id: 39, name: "Malachi", chapters: 4 },
  { id: 40, name: "Matthew", chapters: 28 },
  { id: 41, name: "Mark", chapters: 16 },
  { id: 42, name: "Luke", chapters: 24 },
  { id: 43, name: "John", chapters: 21 },
  { id: 44, name: "Acts", chapters: 28 },
  { id: 45, name: "Romans", chapters: 16 },
  { id: 46, name: "1 Corinthians", chapters: 16 },
  { id: 47, name: "2 Corinthians", chapters: 13 },
  { id: 48, name: "Galatians", chapters: 6 },
  { id: 49, name: "Ephesians", chapters: 6 },
  { id: 50, name: "Philippians", chapters: 4 },
  { id: 51, name: "Colossians", chapters: 4 },
  { id: 52, name: "1 Thessalonians", chapters: 5 },
  { id: 53, name: "2 Thessalonians", chapters: 3 },
  { id: 54, name: "1 Timothy", chapters: 6 },
  { id: 55, name: "2 Timothy", chapters: 4 },
  { id: 56, name: "Titus", chapters: 3 },
  { id: 57, name: "Philemon", chapters: 1 },
  { id: 58, name: "Hebrews", chapters: 13 },
  { id: 59, name: "James", chapters: 5 },
  { id: 60, name: "1 Peter", chapters: 5 },
  { id: 61, name: "2 Peter", chapters: 3 },
  { id: 62, name: "1 John", chapters: 5 },
  { id: 63, name: "2 John", chapters: 1 },
  { id: 64, name: "3 John", chapters: 1 },
  { id: 65, name: "Jude", chapters: 1 },
  { id: 66, name: "Revelation", chapters: 22 }
];

const VERSIONS = [
  { value: "KJV", label: "KJV" },
  { value: "CUV", label: "CUV" },
  { value: "ESV", label: "ESV" }
];

function buildEndpointCandidates(book, chapter, version) {
  const query = `version=${encodeURIComponent(version)}`;
  return [
    `/api/bible/${book}/${chapter}?${query}`,
    `/bibleapi.php?__route__=/api/bible/${book}/${chapter}&${query}`
  ];
}

async function fetchChapter(book, chapter, version) {
  const candidates = buildEndpointCandidates(book, chapter, version);
  let lastError = null;

  for (const url of candidates) {
    try {
      const response = await fetch(url, {
        headers: {
          Accept: "application/json"
        }
      });

      if (!response.ok) {
        throw new Error(`Request failed with ${response.status}`);
      }

      return await response.json();
    } catch (error) {
      lastError = error;
    }
  }

  throw lastError || new Error("Unable to load chapter");
}

function VerseList({ rows }) {
  if (!rows || rows.length === 0) {
    return (
      <div className="message-box">
        No verses were returned for this selection.
      </div>
    );
  }

  if (rows[0]?.verse === "htmltext") {
    return (
      <div
        className="html-passage"
        dangerouslySetInnerHTML={{ __html: rows[0].text }}
      />
    );
  }

  const filteredRows = rows.filter((row) => row.verse !== "chaptertitle");

  return (
    <div className="verse-list">
      {filteredRows.map((row, index) => (
        <article
          className="verse-row"
          key={`${row.chapter || "c"}-${row.verse || index}`}
        >
          <div className="verse-number">{row.verse}</div>
          <div className="verse-text">{row.text}</div>
        </article>
      ))}
    </div>
  );
}

function App() {
  const [book, setBook] = useState(43);
  const [chapter, setChapter] = useState(1);
  const [version, setVersion] = useState("KJV");
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState("");
  const [payload, setPayload] = useState(null);

  const selectedBook = useMemo(
    () => BOOKS.find((entry) => entry.id === Number(book)) || BOOKS[0],
    [book]
  );

  const chapterOptions = useMemo(
    () => Array.from({ length: selectedBook.chapters }, (_, index) => index + 1),
    [selectedBook]
  );

  useEffect(() => {
    if (chapter > selectedBook.chapters) {
      setChapter(1);
    }
  }, [chapter, selectedBook]);

  async function loadChapter(nextBook = book, nextChapter = chapter, nextVersion = version) {
    setLoading(true);
    setError("");

    try {
      const data = await fetchChapter(nextBook, nextChapter, nextVersion);
      setPayload(data);
    } catch (loadError) {
      setPayload(null);
      setError(loadError.message || "Unable to load chapter");
    } finally {
      setLoading(false);
    }
  }

  useEffect(() => {
    loadChapter();
  }, []);

  function handleSubmit(event) {
    event.preventDefault();
    loadChapter(book, chapter, version);
  }

  function handleRandomChapter() {
    const randomBook = BOOKS[Math.floor(Math.random() * BOOKS.length)];
    const randomChapter = Math.floor(Math.random() * randomBook.chapters) + 1;

    setBook(randomBook.id);
    setChapter(randomChapter);
    loadChapter(randomBook.id, randomChapter, version);
  }

  return (
    <main className="app-shell">
      <section className="hero">
        <div className="hero-card">
          <p className="eyebrow">React Reader</p>
          <h1>Bible API, rebuilt as a separate React client.</h1>
          <p>
            This page talks to the existing PHP Bible endpoint and presents the
            chapter data in a cleaner reader experience. It lives in its own
            directory so we can iterate on it without touching the legacy pages.
          </p>
        </div>
      </section>

      <section className="reader-grid">
        <aside>
          <form className="reader-card" onSubmit={handleSubmit}>
            <h2 className="card-title">Open a passage</h2>

            <div className="control-group">
              <label className="control-label" htmlFor="book-select">Book</label>
              <select
                id="book-select"
                className="select"
                value={book}
                onChange={(event) => setBook(Number(event.target.value))}
              >
                {BOOKS.map((entry) => (
                  <option key={entry.id} value={entry.id}>
                    {entry.name}
                  </option>
                ))}
              </select>
            </div>

            <div className="control-group">
              <label className="control-label" htmlFor="chapter-select">Chapter</label>
              <select
                id="chapter-select"
                className="select"
                value={chapter}
                onChange={(event) => setChapter(Number(event.target.value))}
              >
                {chapterOptions.map((value) => (
                  <option key={value} value={value}>
                    {value}
                  </option>
                ))}
              </select>
            </div>

            <div className="control-group">
              <label className="control-label" htmlFor="version-select">Version</label>
              <select
                id="version-select"
                className="select"
                value={version}
                onChange={(event) => setVersion(event.target.value)}
              >
                {VERSIONS.map((entry) => (
                  <option key={entry.value} value={entry.value}>
                    {entry.label}
                  </option>
                ))}
              </select>
            </div>

            <div className="action-row">
              <button className="button button-primary" type="submit" disabled={loading}>
                {loading ? "Loading..." : "Load chapter"}
              </button>
              <button
                className="button button-secondary"
                type="button"
                onClick={handleRandomChapter}
                disabled={loading}
              >
                Random chapter
              </button>
            </div>
          </form>

          <div className="help-card">
            <p>
              Endpoint fallback order: pretty route first, then
              <code> bibleapi.php?__route__=...</code> if needed.
            </p>
          </div>
        </aside>

        <section className="verses-card">
          <div className="status-pill">
            {version} • {selectedBook.name} {chapter}
          </div>

          <h2 className="chapter-title">
            {payload?.title || `${selectedBook.name} ${chapter}`}
          </h2>

          <p className="chapter-subtitle">
            {loading
              ? "Fetching chapter text from the existing Bible API."
              : "Rendered from the legacy PHP endpoint inside a new React interface."}
          </p>

          {error ? (
            <div className="message-box">
              <strong>Request failed.</strong>
              <div>{error}</div>
            </div>
          ) : loading ? (
            <div className="message-box">Loading chapter...</div>
          ) : (
            <VerseList rows={payload?.rows || []} />
          )}
        </section>
      </section>
    </main>
  );
}

const root = ReactDOM.createRoot(document.getElementById("root"));
root.render(<App />);
