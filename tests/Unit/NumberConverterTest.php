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

describe('NumberConverter::formatCurrency', function () {
    it('formats numbers by currency code', function () {
        expect(NumberConverter::formatCurrency(1234567.89, 'INR'))->toBe('₹12,34,567.89');
        expect(NumberConverter::formatCurrency(1234567.89, 'USD'))->toBe('$1,234,567.89');
        expect(NumberConverter::formatCurrency(1234567.89, 'EUR'))->toBe('€1,234,567.89');
        expect(NumberConverter::formatCurrency(1234567.89, 'JPY'))->toBe('¥1,234,568');
        expect(NumberConverter::formatCurrency(1234567.89, 'AED', false))->toBe('AED 1,234,567.89');
    });

    it('throws exception for unsupported currency codes', function () {
        $this->expectException(InvalidArgumentException::class);
        NumberConverter::formatCurrency(1234, 'XYZ');
    });
});

describe('NumberConverter::numberToCurrencyWords', function () {
    it('converts amounts to currency words by currency code', function () {
        expect(NumberConverter::numberToCurrencyWords(12345678.89, 'INR'))->toBe('one crore, twenty-three lakh, forty-five thousand, six hundred and seventy-eight rupees and eighty-nine paise');
        expect(NumberConverter::numberToCurrencyWords(1234567.89, 'USD'))->toBe('one million, two hundred and thirty-four thousand, five hundred and sixty-seven dollars and eighty-nine cents');
        expect(NumberConverter::numberToCurrencyWords(1234567.89, 'JPY'))->toBe('one million, two hundred and thirty-four thousand, five hundred and sixty-eight yen');
    });

    it('throws exception for unsupported currency word codes', function () {
        $this->expectException(InvalidArgumentException::class);
        NumberConverter::numberToCurrencyWords(1234, 'XYZ');
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
        expect($converter->wordsToNumber('one lakh'))->toBe(100000);
        expect($converter->wordsToNumber('twelve lakh thirty-four thousand five hundred and sixty-seven'))->toBe(1234567);
        expect($converter->wordsToNumber('one crore twenty-three lakh forty-five thousand six hundred and seventy-eight'))->toBe(12345678);
    });
});
