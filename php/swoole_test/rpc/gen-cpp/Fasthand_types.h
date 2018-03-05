/**
 * Autogenerated by Thrift Compiler (0.9.3)
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *  @generated
 */
#ifndef Fasthand_TYPES_H
#define Fasthand_TYPES_H

#include <iosfwd>

#include <thrift/Thrift.h>
#include <thrift/TApplicationException.h>
#include <thrift/protocol/TProtocol.h>
#include <thrift/transport/TTransport.h>

#include <thrift/cxxfunctional.h>
#include "shared_types.h"


namespace Fasthand {

class Area;

typedef struct _Area__isset {
  _Area__isset() : code(false), name(false), type(false), p_code(false) {}
  bool code :1;
  bool name :1;
  bool type :1;
  bool p_code :1;
} _Area__isset;

class Area {
 public:

  Area(const Area&);
  Area& operator=(const Area&);
  Area() : code(0), name(), type(0), p_code(0) {
  }

  virtual ~Area() throw();
  int32_t code;
  std::string name;
  int8_t type;
  int32_t p_code;

  _Area__isset __isset;

  void __set_code(const int32_t val);

  void __set_name(const std::string& val);

  void __set_type(const int8_t val);

  void __set_p_code(const int32_t val);

  bool operator == (const Area & rhs) const
  {
    if (!(code == rhs.code))
      return false;
    if (!(name == rhs.name))
      return false;
    if (!(type == rhs.type))
      return false;
    if (!(p_code == rhs.p_code))
      return false;
    return true;
  }
  bool operator != (const Area &rhs) const {
    return !(*this == rhs);
  }

  bool operator < (const Area & ) const;

  uint32_t read(::apache::thrift::protocol::TProtocol* iprot);
  uint32_t write(::apache::thrift::protocol::TProtocol* oprot) const;

  virtual void printTo(std::ostream& out) const;
};

void swap(Area &a, Area &b);

inline std::ostream& operator<<(std::ostream& out, const Area& obj)
{
  obj.printTo(out);
  return out;
}

} // namespace

#endif
