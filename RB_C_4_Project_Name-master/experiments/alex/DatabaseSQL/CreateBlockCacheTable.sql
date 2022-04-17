CREATE TABLE BLOCK_CACHE (
      dev_edit_id INT,
      chunk_id VARCHAR(64),
      FOREIGN KEY (chunk_id) REFERENCES CHUNKS(id)
)