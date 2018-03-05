namespace php Fasthand.Service.Common

struct SData {
1: string __SERVICENAME;
2: string __METHOD;
3:optional string __PARAM;
}

service CommonService {
    string commonMethod(1: SData sdata)
    oneway void asyncCommonMethod(1: SData sdata)
}
