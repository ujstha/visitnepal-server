CREATE TABLE adventure (
    id               INTEGER NOT NULL AUTO_INCREMENT,
    title            VARCHAR(50)  NOT NULL,
    description      VARCHAR(1000) NOT NULL,
    street           VARCHAR(100) NOT NULL,
    province         VARCHAR(100) NOT NULL,
    zip              VARCHAR(50)  NOT NULL,
    group_size       VARCHAR(100) NOT NULL,
    activity_type    VARCHAR(100) NOT NULL,
    activity_level   VARCHAR(100) NOT NULL,
    date_added       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    image            VARCHAR(8000) NOT NULL,
    image1           VARCHAR(8000) NOT NULL,
    image2           VARCHAR(8000),
    image3           VARCHAR(8000),
    adventure_visit_counter INTEGER NOT NULL DEFAULT 0,
    adventure_comment_counter INTEGER NOT NULL DEFAULT 0,
    adventure_rating_counter  INTEGER NOT NULL DEFAULT 0,
    PRIMARY KEY ( id )
);

CREATE TABLE customer (
    id              INTEGER NOT NULL AUTO_INCREMENT,
    first_name      VARCHAR(50) NOT NULL,
    last_name       VARCHAR(50) NOT NULL,
    email_address   VARCHAR(50) NOT NULL,
    gender          VARCHAR(10),
    age             INTEGER,
    country         VARCHAR(50),
    password        VARCHAR(50),
    decrypt_pass    VARCHAR(50),
    profile_pic     VARCHAR(8000) NOT NULL,
    signed_on       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY ( id ),
    UNIQUE ( email_address )
);

CREATE TABLE comment (
    id              INTEGER NOT NULL AUTO_INCREMENT,
    commenter       VARCHAR(1000) NOT NULL,
    commenter_pic   VARCHAR(8000) NOT NULL,
    comment_txt     LONGTEXT NOT NULL,
    date_commented  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    adventure_id    INTEGER,
    customer_id     INTEGER,
    PRIMARY KEY ( id ),
    FOREIGN KEY ( adventure_id ) REFERENCES adventure( id ),
    FOREIGN KEY ( customer_id ) REFERENCES customer( id )
);

CREATE TABLE rating (
    id              INTEGER NOT NULL AUTO_INCREMENT,
    rating          INTEGER NOT NULL,
    date_rated      TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    adventure_id    INTEGER,
    customer_id     INTEGER,
    PRIMARY KEY ( id ),
    FOREIGN KEY ( adventure_id ) REFERENCES adventure( id ),
    FOREIGN KEY ( customer_id ) REFERENCES customer( id )
);

CREATE TABLE admin (
    adminuser      VARCHAR(50) NOT NULL,
    adminpassword  VARCHAR(50) NOT NULL,
    typeofuser     VARCHAR(50) NOT NULL
);

INSERT INTO admin (`adminuser`, `adminpassword`, `typeofuser`) VALUES
('admin', 'admin', 'Admin');

CREATE TABLE advertisement (
    id             INTEGER      NOT NULL AUTO_INCREMENT,
    link           VARCHAR(255) NOT NULL,
    image          VARCHAR(8000)     NOT NULL,
    detail         LONGTEXT     NOT NULL,
    PRIMARY KEY ( id )
);

CREATE TABLE event (
    id               INTEGER NOT NULL AUTO_INCREMENT,
    title            VARCHAR(50)  NOT NULL,
    description      VARCHAR(1000) NOT NULL,
    street           VARCHAR(100) NOT NULL,
    province         VARCHAR(100) NOT NULL,
    zip              VARCHAR(50)  NOT NULL,
    group_size       VARCHAR(100) NOT NULL,
    activity_type    VARCHAR(100) NOT NULL,
    activity_level   VARCHAR(100) NOT NULL,
    date_added       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    image            VARCHAR(8000) NOT NULL,
    image1           VARCHAR(8000) NOT NULL,
    image2           VARCHAR(8000),
    creator_pic      VARCHAR(8000) NOT NULL,
    creator          VARCHAR(255)  NOT NULL,
    event_visit_counter INTEGER NOT NULL DEFAULT 0,
    customer_id     INTEGER,
    PRIMARY KEY ( id ),
    FOREIGN KEY ( customer_id ) REFERENCES customer( id )
);
