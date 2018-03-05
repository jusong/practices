<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements. See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership. The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License. You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied. See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @package thrift.transport
 */

namespace Thrift\Transport;

use Thrift\Exception\TException;
use Thrift\Exception\TTransportException;
use Thrift\Factory\TStringFuncFactory;

/**
 * Sockets implementation of the TTransport interface.
 *
 * @package thrift.transport
 */
class TBufferSocket extends TTransport
{
  /**
   * Input Buffer
   *
   * @var string
   */
  private $input_ = null;

  /**
   * Output Buffer
   *
   * @var string
   */
  private $output_ = "";

  /**
   * Socket constructor
   *
   * @param string $host Remote hostname
   * @param int $port Remote port
   * @param bool $persist Whether to use a persistent socket
   * @param string $debugHandler Function to call for error logging
   */
  public function __construct($input = null)
  {
    $this->input_ = $input;
  }

  /**
   * @param string $input
   * @return void
   */
  public function setInput($input)
  {
    $this->input_ = $input;
  }

  /**
   * @return string
   */
  public function getOutput()
  {
    return $this->output_;
  }

  /**
   * Tests whether this is open
   *
   * @return bool true if the socket is open
   */
  public function isOpen()
  {
    return $this->input_ === null;
  }

  /**
   * Connects the socket.
   */
  public function open()
  {
    if ($this->input_ === null) {
      throw new TException("TBufferSocket: Input Buffer is null");
    }
  }

  /**
   * Closes the socket.
   */
  public function close()
  {
    $this->input_ = null;
  }

  /**
   * Read from the socket at most $len bytes.
   *
   * This method will not wait for all the requested data, it will return as
   * soon as any data is received.
   *
   * @param int $len Maximum number of bytes to read.
   * @return string Binary data
   */
  public function read($len)
  {
    if ($this->input_ !== null) {
      $length = TStringFuncFactory::create()->strlen($this->input_);
      if ($length <= 0) {
        throw new TTransportException('TBufferSocket: Input Buffer is empty');
      } else if ($length < $len) {
        $data = $this->input_;
        $this->input_ = "";
      } else {
        $data = TStringFuncFactory::create()->substr($this->input_, 0, $len);
        $this->input_ = TStringFuncFactory::create()->substr($this->input_, $len);
      }
      return $data;
    } else {
      throw new TTransportException('TBufferSocket: Input Buffer is null');
    }
  }

  /**
   * Write to the socket.
   *
   * @param string $buf The data to write
   */
  public function write($buf)
  {
    if (TStringFuncFactory::create()->strlen($buf) > 0) {
      $this->output_ .= $buf;
    }
  }

  /**
   * Flush output to the socket.
   *
   * Since read(), readAll() and write() operate on the sockets directly,
   * this is a no-op
   *
   * If you wish to have flushable buffering behaviour, wrap this TSocket
   * in a TBufferedTransport.
   */
  public function flush()
  {
    // no-op
  }
}