
DROP TABLE IF EXISTS kc_setting;

-- Account table.
CREATE TABLE IF NOT EXISTS kc_setting (
    parent_account_id INTEGER,
    key VARCHAR(255),
    value TEXT,
    value_index INTEGER,
    PRIMARY KEY (parent_account_id, key, value_index)
);

