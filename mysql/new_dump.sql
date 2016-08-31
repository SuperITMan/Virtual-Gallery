--
-- Base de données :  'Virtual-Gallery'
--

CREATE DATABASE IF NOT EXISTS virtual_gallery
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;
USE virtual_gallery;

-- --------------------------------------------------------

--
-- Structure de la table 'vg_users'
--

CREATE TABLE IF NOT EXISTS vg_users (
  id INTEGER AUTO_INCREMENT NOT NULL,
  username VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  passwd VARCHAR(255) NOT NULL,
  displayed_name VARCHAR(255) NOT NULL,
  is_admin VARCHAR(6) NOT NULL,
  is_super_admin VARCHAR(6) NOT NULL,
  UNIQUE (username),
  UNIQUE (email),
  PRIMARY KEY (id)
);

-- --------------------------------------------------------

--
-- Structure de la table `vg_users_personal_info`
--

CREATE TABLE IF NOT EXISTS vg_users_personal_info (
  id INTEGER AUTO_INCREMENT NOT NULL,
  user_id INTEGER NOT NULL,
  first_name VARCHAR(100) NULL,
  last_name VARCHAR(200) NULL,
  birthday_date DATE NULL,
  city VARCHAR(150) NULL,
  country VARCHAR(150) NULL,
  UNIQUE(user_id),
  FOREIGN KEY (user_id) REFERENCES vg_users(id) ON DELETE CASCADE,
  PRIMARY KEY (id)
);

-- --------------------------------------------------------

--
-- Structure de la table `vg_users_meta`
--

CREATE TABLE IF NOT EXISTS vg_users_meta (
  id INTEGER AUTO_INCREMENT NOT NULL,
  user_id INTEGER NOT NULL,
  meta_key VARCHAR(255) NOT NULL,
  meta_value MEDIUMTEXT NOT NULL,
  UNIQUE (user_id, meta_key),
  FOREIGN KEY (user_id) REFERENCES vg_users(id) ON DELETE CASCADE,
  PRIMARY KEY (id)
);

-- --------------------------------------------------------

--
-- Structure de la table `vg_creations`
--

CREATE TABLE IF NOT EXISTS vg_creations (
  id INTEGER AUTO_INCREMENT NOT NULL,
  name VARCHAR(100) NOT NULL,
  short_description VARCHAR(254) NULL,
  long_description TEXT NULL,
  creation_type VARCHAR(254) NULL,
  creation_date DATETIME NULL,
  date_added DATETIME NOT NULL,
  used_materials TEXT NULL,
  image_ids TEXT NULL,
  user_id INTEGER NOT NULL,
  FOREIGN KEY (user_id) REFERENCES vg_users(id),
  PRIMARY KEY (id)
);

-- --------------------------------------------------------

--
-- Structure de la table `vg_creations_meta`
--

CREATE TABLE IF NOT EXISTS vg_creations_meta (
  id INTEGER AUTO_INCREMENT NOT NULL,
  creation_id INTEGER NOT NULL,
  meta_key VARCHAR(255) NOT NULL,
  meta_value MEDIUMTEXT NOT NULL,
  UNIQUE (creation_id, meta_key),
  FOREIGN KEY (creation_id) REFERENCES vg_creations(id),
  PRIMARY KEY (id)
);

-- --------------------------------------------------------

--
-- Structure de la table `vg_comments`
--

CREATE TABLE IF NOT EXISTS vg_comments (
  id INTEGER AUTO_INCREMENT NOT NULL,
  creation_id INTEGER NOT NULL,
  comment TEXT NOT NULL,
  user_displayed_name VARCHAR(255) NOT NULL,
  user_email VARCHAR(255) NOT NULL,
  date_added DATETIME NOT NULL,
  FOREIGN KEY (creation_id) REFERENCES vg_creations(id),
  PRIMARY KEY (id)
);

-- --------------------------------------------------------

--
-- Structure de la table `vg_creations`
--

CREATE TABLE IF NOT EXISTS vg_categories (
  id INTEGER AUTO_INCREMENT NOT NULL,
  name VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
);

-- --------------------------------------------------------

--
-- Structure de la table `vg_categories_creations`
--

CREATE TABLE IF NOT EXISTS vg_categories_creations (
  id INTEGER AUTO_INCREMENT NOT NULL,
  creation_id INTEGER NOT NULL,
  category_id INTEGER NOT NULL,
  UNIQUE(creation_id, category_id),
  FOREIGN KEY (creation_id) REFERENCES vg_creations(id),
  FOREIGN KEY (category_id) REFERENCES vg_categories(id),
  PRIMARY KEY (id)
);

-- --------------------------------------------------------

--
-- Structure de la table `vg_categories_meta`
--

CREATE TABLE IF NOT EXISTS vg_categories_meta (
  id INTEGER AUTO_INCREMENT NOT NULL,
  category_id INTEGER NOT NULL,
  meta_key VARCHAR(255) NOT NULL,
  meta_value MEDIUMTEXT NOT NULL,
  UNIQUE (category_id, meta_key),
  FOREIGN KEY (category_id) REFERENCES vg_categories(id),
  PRIMARY KEY (id)
);

-- --------------------------------------------------------

--
-- Structure de la table `vg_news`
--

CREATE TABLE IF NOT EXISTS vg_news (
  id INTEGER AUTO_INCREMENT NOT NULL,
  title VARCHAR(255) NOT NULL,
  content MEDIUMTEXT NOT NULL,
  creation_date DATETIME NOT NULL,
  PRIMARY KEY (id)
);

-- --------------------------------------------------------

# --
# -- Structure de la table `vg_options`
# --
#
# CREATE TABLE IF NOT EXISTS vg_options (
#   id INTEGER AUTO_INCREMENT NOT NULL,
#   copyright VARCHAR(254) NOT NULL,
#   site_name VARCHAR(254) NOT NULL,
#   site_image_id INTEGER NULL,
#   about_us_page TEXT NULL,
#   FOREIGN KEY (site_image_id) REFERENCES vg_uploaded_files(id),
#   PRIMARY KEY (id)
# );

-- --------------------------------------------------------

--
-- Structure de la table `vg_options_meta`
--

CREATE TABLE IF NOT EXISTS vg_options_meta (
  id INTEGER AUTO_INCREMENT NOT NULL,
  meta_key VARCHAR(255) NOT NULL,
  meta_value MEDIUMTEXT NOT NULL,
  UNIQUE (meta_key),
  PRIMARY KEY (id)
);


-- --------------------------------------------------------

--
-- Structure de la table `vg_uploaded_files`
--

CREATE TABLE IF NOT EXISTS vg_uploaded_files (
  id integer AUTO_INCREMENT NOT NULL,
  file_name VARCHAR(254) NOT NULL,
  server_file_name VARCHAR(254) NOT NULL,
  mime VARCHAR(50) NOT NULL,
  upload_date DATETIME NULL,
  user_id INTEGER NOT NULL,
  thumbnail VARCHAR(254) NOT NULL,
  FOREIGN KEY (user_id) REFERENCES vg_users(id),
  PRIMARY KEY (id)
);

-- --------------------------------------------------------

--
-- Structure de la table `vg_uploaded_files_meta`
--

CREATE TABLE IF NOT EXISTS vg_uploaded_files_meta (
  id integer AUTO_INCREMENT NOT NULL,
  file_id INTEGER NOT NULL,
  meta_key VARCHAR(255) NOT NULL,
  meta_value MEDIUMTEXT NOT NULL,
  UNIQUE (file_id, meta_key),
  FOREIGN KEY (file_id) REFERENCES vg_uploaded_files(id),
  PRIMARY KEY (id)
);