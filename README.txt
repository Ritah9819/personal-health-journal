================================================
  HEALTH JOURNAL — Personal Health Record System
  Complete setup guide (InfinityFree)
================================================

WHAT'S IN THIS ZIP
------------------
  index.php      Homepage (hero + features)
  login.php      Log in page
  register.php   Create account page
  dashboard.php  User dashboard + health records
  layout.php     Shared header/footer + branding
  logout.php     Log out
  db.php         Database connection helper
  config.php     Database settings (EDIT THIS)
  schema.sql     Database structure (import this)
  css/style.css  All styling

------------------------------------------------
STEP 1 — Upload the files
------------------------------------------------
1. Extract this zip on your computer/phone.
2. Open your InfinityFree control panel.
3. Go to "File Manager" (or use an FTP client).
4. Open the "htdocs" folder.
5. Upload ALL the files/folders from this zip
   into htdocs (not inside a subfolder).

------------------------------------------------
STEP 2 — Create the database
------------------------------------------------
1. In InfinityFree control panel, go to
   "MySQL Databases".
2. Create a new database (note its full name,
   e.g.  if0_43411968_healthjournal).
3. Open phpMyAdmin (button next to your DB).
4. Select your database on the left.
5. Click the "Import" tab.
6. Choose the schema.sql file from this zip.
7. Click "Go" — this creates the tables.

------------------------------------------------
STEP 3 — Edit config.php
------------------------------------------------
Open config.php and set:
  'name' => 'if0_43411968_healthjournal'   <- your full DB name
  'pass' => 'your_vpanel_password'          <- your vPanel password
  'debug' => false                          <- set to false when live

------------------------------------------------
STEP 4 — You're live!
------------------------------------------------
Visit your site (http://your-site.infinityfreeapp.com).
  - Click "Get started" to create an account.
  - Log in, add health records from the dashboard.

------------------------------------------------
TROUBLESHOOTING
------------------------------------------------
- "Database connection failed" -> check config.php name/pass.
- Blank page -> set 'debug' => true in config.php to see the error.
- If you use a subfolder, make sure all links still work
  (the app uses relative links, so it works from any folder).
- The homepage always shows first; once logged in it greets
  you with an "Open my dashboard" button.

------------------------------------------------
FILES: what each page does
------------------------------------------------
- index.php      Landing page (always shows first).
- login.php      Sign in. After success -> dashboard.
- register.php   Create account. After success -> login.
- dashboard.php  Shows stats + add/delete health records.
- logout.php     Ends session, returns to homepage.

Enjoy! If you get stuck, check the error message in
config.php's debug mode.
