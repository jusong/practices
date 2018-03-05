#!/bin/bash

function f1() {
	echo 'f1';
	f2
}

function f2() {
	echo 'f2';
}

f1
