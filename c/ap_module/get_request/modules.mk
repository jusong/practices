mod_get_request.la: mod_get_request.slo
	$(SH_LINK) -rpath $(libexecdir) -module -avoid-version  mod_get_request.lo
DISTCLEAN_TARGETS = modules.mk
shared =  mod_get_request.la
