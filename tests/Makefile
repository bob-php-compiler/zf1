FILES = run-test.php test-files

test: $(FILES)
	bpc -v \
	    -c ../library/Zend/zend.bpc.conf \
	    -o test \
	    -u phpunit \
	    -u zend \
	    -d display_errors=on \
	    -d max_execution_time=-1 \
	    --php-exts .php,.inc,.phtml,.dist \
	    run-test.php \
	    --input-file test-files \
	    --input-file resource-files

clean:
	@rm -rf .bpc-build-* md5.map
	@rm -fv $(FILES) TESTCASE_LIST.php test
	@rm -rf MockClassFile
