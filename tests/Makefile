FILES = run-test.php test-files

test: $(FILES)
	bpc -v \
	    -o test \
	    -u phpunit \
	    -u zend \
	    -d display_errors=on \
	    --php-exts .php,.inc,.phtml,.dist \
	    run-test.php \
	    --input-file test-files

clean:
	@rm -rf .bpc-build-* md5.map
	@rm -fv $(FILES) test
	@rm -rf MockClassFile
