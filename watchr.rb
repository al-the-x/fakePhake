#!/usr/bin/env ruby

def test ( filename )
    filename.gsub!(/\.php$/, 'Test.php') if !filename.match(/Test\.php$/);

    system("clear; ./test.sh #{filename}")
end

watch( '.*\.php$' ) {| matches | test(matches[0]) }

