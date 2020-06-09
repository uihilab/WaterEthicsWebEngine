-- Table: response_table

-- DROP TABLE response_table;

CREATE TABLE response_table
(
  uid character varying,
  scenario integer,
  response character(1),
  click boolean -- true if text description displayed.
)
WITH (
  OIDS=FALSE
);
ALTER TABLE response_table
  OWNER TO {owner name};
COMMENT ON COLUMN response_table.click IS 'true if text description displayed.';