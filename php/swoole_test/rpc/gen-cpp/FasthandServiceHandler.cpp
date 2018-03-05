#include "FasthandServiceHandler.h"

namespace Fasthand {

void FasthandServiceHandler::getUserInfo(std::map<std::string, std::string> & _return, const int32_t userId) {
	std::cout << "getUserInfo" << std::endl;
}
void FasthandServiceHandler::getAreaInfo(std::vector<Area> & _return) {
	std::cout << "getAreaInfo" << std::endl;
}
void FasthandServiceHandler::getAreaNameList(std::vector< ::shared::SharedStruct> & _return) {
	std::cout << "getAreaNameList" << std::endl;
}
int64_t FasthandServiceHandler::getTime() {
	std::cout << "getTime" << std::endl;
	return time(0);	
}
void FasthandServiceHandler::upFirstChar(std::map<std::string, std::string> & _return, const std::map<std::string, std::string> & paramArray) {
	std::cout << "upFirstChar" << std::endl;
}

}
