<?php

use Jayen\NumberConverter\NumberConverter;

describe('NumberConverter::numberToWords', function () {
    it('converts numbers to words', function () {
        expect(NumberConverter::numberToWords(0))->toBe('zero');
        expect(NumberConverter::numberToWords(5))->toBe('five');
        expect(NumberConverter::numberToWords(19))->toBe('nineteen');
        expect(NumberConverter::numberToWords(21))->toBe('twenty-one');
        expect(NumberConverter::numberToWords(105))->toBe('one hundred and five');
        expect(NumberConverter::numberToWords(569))->toBe('five hundred and sixty-nine');
        expect(NumberConverter::numberToWords(1000))->toBe('one thousand');
        expect(NumberConverter::numberToWords(1234))->toBe('one thousand, two hundred and thirty-four');
        expect(NumberConverter::numberToWords(-7))->toBe('negative seven');
    });

    it('throws exception for non-numeric input', function () {
        $this->expectException(InvalidArgumentException::class);
        NumberConverter::numberToWords('foo');
    });
});

describe('NumberConverter::wordsToNumber', function () {
    it('converts words to numbers', function () {
        $converter = new NumberConverter();
        expect($converter->wordsToNumber('zero'))->toBe(0);
        expect($converter->wordsToNumber('five'))->toBe(5);
        expect($converter->wordsToNumber('nineteen'))->toBe(19);
        expect($converter->wordsToNumber('twenty-one'))->toBe(21);
        expect($converter->wordsToNumber('one hundred and five'))->toBe(105);
        expect($converter->wordsToNumber('five hundred and sixty-nine'))->toBe(569);
        expect($converter->wordsToNumber('one thousand'))->toBe(1000);
        expect($converter->wordsToNumber('one thousand two hundred and thirty-four'))->toBe(1234);
    });
});
