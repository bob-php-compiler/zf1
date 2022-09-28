libzend_u-4.4a.so:
	bpc -v \
		-l zend \
		--copt -Wno-trigraphs \
		--input-file src.list \
		--input-file resource.list \
		--uniq-class-list uniq-class.list

install:
	bpc -l zend --install

clean:
	@rm -rf .bpc-build-* md5.map
	@rm -fv libzend_u-4.4a.so libzend_u-4.4a.a zend.heap zend.sch
