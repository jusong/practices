#include "httpd.h"
#include "http_config.h"
#include "http_core.h"

void test_register_hook(apr_pool_t *p) {
  printf("test register hook\n");
}

AP_MODULE_DECLARE_DATA module test_module = {
  STANDARD20_MODULE_STUFF,
  NULL,
  NULL,
  NULL,
  NULL,
  NULL,
  test_register_hook
};
