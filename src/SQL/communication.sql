
-- Email table for kinicart.
CREATE TABLE kc_email (
    id  INTEGER PRIMARY KEY AUTOINCREMENT,
    account_id  INTEGER,
    sent_date    DATETIME,
    sender VARCHAR(255),
    recipient VARCHAR(255),
    cc VARCHAR(255),
    bcc VARCHAR(255),
    subject VARCHAR(255),
    reply_to VARCHAR(255),
    text_body TEXT,
    status VARCHAR(255),
    error_message VARCHAR(255)
);
