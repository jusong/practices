#ifndef FASTHANDSERVICEHANDLER_H
#define FASTHANDSERVICEHANDLER_H

#include "FasthandService.h"
#include "SharedService.h"
#include <time.h>

namespace Fasthand {

using namespace shared;

class FasthandServiceHandler : virtual public FasthandServiceIf {
 public:
  FasthandServiceHandler() {}
  void getUserInfo(std::map<std::string, std::string> & _return, const int32_t userId);
  void getAreaInfo(std::vector<Area> & _return);
  void getAreaNameList(std::vector< ::shared::SharedStruct> & _return);
  int64_t getTime();
  void upFirstChar(std::map<std::string, std::string> & _return, const std::map<std::string, std::string> & paramArray);
};

}

#endif
