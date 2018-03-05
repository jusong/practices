#ifndef CALCULATORSERVICEHANDLER_H
#define CALCULATORSERVICEHANDLER_H

#include "Calculator.h"
#include "SharedService.h"

namespace tutorial {

using namespace shared;

class CalculatorHandler : virtual public CalculatorIf {
 public:
  CalculatorHandler() {}

  void ping() {
    printf("ping\n");
  }

  int32_t add(const int32_t num1, const int32_t num2) {
    printf("add\n");
	return num1 + num2;
  }

  int32_t calculate(const int32_t logid, const Work& w) {
    printf("calculate\n");
  }

  void zip() {
    printf("zip\n");
  }

  void getStruct(SharedStruct& _return, const int32_t key) {
    printf("getStruct\n");
  }
};

}
#endif
