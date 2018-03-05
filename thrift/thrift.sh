#!/bin/bash
# 分别为User和Article两个服务生成客户端和服务端框架代码

thrift --gen java FasthandUser.thrift
thrift --gen php FasthandUser.thrift

thrift --gen java FasthandArticle.thrift
thrift --gen php FasthandArticle.thrift
