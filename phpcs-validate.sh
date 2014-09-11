#!/bin/env sh
if [ $# -lt 1 ]; then
	echo -e "Usage: $0 <sniff.name>"
	exit 1;
fi

CS="CodingStandard"
SNIFF=${1#$CS.}
TEST_DATA_FILE="$CS/Tests/${SNIFF/.//}UnitTest.*inc"
REPORT="${2-full}"

echo -e "Report: $REPORT"
vendor/bin/phpcs -vs --report=$REPORT --report-width=120 --standard=$CS --sniffs=$CS.$SNIFF $TEST_DATA_FILE
