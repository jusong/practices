include "shared.thrift"

namespace cpp Fasthand
namespace php Fasthand

struct Area {
	1: i32 code,
	2: string name,
	3: byte type,
	4: i32 p_code
}

service FasthandService {
	map<string,string> getUserInfo(1:i32 userId) throws (1:shared.InvalideService ouch),
	list<Area> getAreaInfo() throws (1:shared.InvalideService ouch),
	list<shared.SharedStruct> getAreaNameList() throws (1:shared.InvalideService ouch),
	i64 getTime(),
	map<string,string> upFirstChar(1:map<string,string> paramArray)
}
