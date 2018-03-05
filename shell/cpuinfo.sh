#!/bin/bash
names=$(cat /proc/cpuinfo| grep "model name.*:" | cut -d: -f2 | sort | uniq)
physical_cpu=$(cat /proc/cpuinfo | grep "physical id" | sort | uniq | wc -l)
single_cpu_core=$(cat /proc/cpuinfo | grep "cpu cores" | uniq | cut -d: -f2)
logic_cpu=$(cat /proc/cpuinfo | grep "processor.*:" | wc -l)
total_core=$((physical_cpu*single_cpu_core))
ht=$((logic_cpu/total_core))
echo "CPU名称："
echo "$names" | while read name
do
	echo "	"$name
done 
echo "物理CPU数："$physical_cpu
echo "单一物理CPU核数："$single_cpu_core
echo "总核数："$total_core
echo "单核线程数："$ht
echo "逻辑CPU数："$logic_cpu
