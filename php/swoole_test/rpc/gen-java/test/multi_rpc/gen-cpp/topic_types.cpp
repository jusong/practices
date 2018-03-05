/**
 * Autogenerated by Thrift Compiler (1.0.0-dev)
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *  @generated
 */
#include "topic_types.h"

#include <algorithm>
#include <ostream>

#include <thrift/TToString.h>

namespace multi { namespace service {


Topic::~Topic() throw() {
}


void Topic::__set_id(const int32_t val) {
  this->id = val;
}

void Topic::__set_uid(const int32_t val) {
  this->uid = val;
}

void Topic::__set_name(const std::string& val) {
  this->name = val;
}

void Topic::__set_content(const std::string& val) {
  this->content = val;
}

uint32_t Topic::read(::apache::thrift::protocol::TProtocol* iprot) {

  apache::thrift::protocol::TInputRecursionTracker tracker(*iprot);
  uint32_t xfer = 0;
  std::string fname;
  ::apache::thrift::protocol::TType ftype;
  int16_t fid;

  xfer += iprot->readStructBegin(fname);

  using ::apache::thrift::protocol::TProtocolException;


  while (true)
  {
    xfer += iprot->readFieldBegin(fname, ftype, fid);
    if (ftype == ::apache::thrift::protocol::T_STOP) {
      break;
    }
    switch (fid)
    {
      case 1:
        if (ftype == ::apache::thrift::protocol::T_I32) {
          xfer += iprot->readI32(this->id);
          this->__isset.id = true;
        } else {
          xfer += iprot->skip(ftype);
        }
        break;
      case 2:
        if (ftype == ::apache::thrift::protocol::T_I32) {
          xfer += iprot->readI32(this->uid);
          this->__isset.uid = true;
        } else {
          xfer += iprot->skip(ftype);
        }
        break;
      case 3:
        if (ftype == ::apache::thrift::protocol::T_STRING) {
          xfer += iprot->readString(this->name);
          this->__isset.name = true;
        } else {
          xfer += iprot->skip(ftype);
        }
        break;
      case 4:
        if (ftype == ::apache::thrift::protocol::T_STRING) {
          xfer += iprot->readString(this->content);
          this->__isset.content = true;
        } else {
          xfer += iprot->skip(ftype);
        }
        break;
      default:
        xfer += iprot->skip(ftype);
        break;
    }
    xfer += iprot->readFieldEnd();
  }

  xfer += iprot->readStructEnd();

  return xfer;
}

uint32_t Topic::write(::apache::thrift::protocol::TProtocol* oprot) const {
  uint32_t xfer = 0;
  apache::thrift::protocol::TOutputRecursionTracker tracker(*oprot);
  xfer += oprot->writeStructBegin("Topic");

  xfer += oprot->writeFieldBegin("id", ::apache::thrift::protocol::T_I32, 1);
  xfer += oprot->writeI32(this->id);
  xfer += oprot->writeFieldEnd();

  xfer += oprot->writeFieldBegin("uid", ::apache::thrift::protocol::T_I32, 2);
  xfer += oprot->writeI32(this->uid);
  xfer += oprot->writeFieldEnd();

  xfer += oprot->writeFieldBegin("name", ::apache::thrift::protocol::T_STRING, 3);
  xfer += oprot->writeString(this->name);
  xfer += oprot->writeFieldEnd();

  xfer += oprot->writeFieldBegin("content", ::apache::thrift::protocol::T_STRING, 4);
  xfer += oprot->writeString(this->content);
  xfer += oprot->writeFieldEnd();

  xfer += oprot->writeFieldStop();
  xfer += oprot->writeStructEnd();
  return xfer;
}

void swap(Topic &a, Topic &b) {
  using ::std::swap;
  swap(a.id, b.id);
  swap(a.uid, b.uid);
  swap(a.name, b.name);
  swap(a.content, b.content);
  swap(a.__isset, b.__isset);
}

Topic::Topic(const Topic& other0) {
  id = other0.id;
  uid = other0.uid;
  name = other0.name;
  content = other0.content;
  __isset = other0.__isset;
}
Topic& Topic::operator=(const Topic& other1) {
  id = other1.id;
  uid = other1.uid;
  name = other1.name;
  content = other1.content;
  __isset = other1.__isset;
  return *this;
}
void Topic::printTo(std::ostream& out) const {
  using ::apache::thrift::to_string;
  out << "Topic(";
  out << "id=" << to_string(id);
  out << ", " << "uid=" << to_string(uid);
  out << ", " << "name=" << to_string(name);
  out << ", " << "content=" << to_string(content);
  out << ")";
}

}} // namespace
