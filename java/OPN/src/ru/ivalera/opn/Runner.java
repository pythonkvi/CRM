package ru.ivalera.opn;


public class Runner {
	public static void main(String... args) {
		String inputStr = "11+(exp(2.010635+sin(PI/2)*3)+50)/2".replace(" ", "");
		Parser p = new Parser();
		Calculator c = new Calculator();
		try {
			System.out.println(String.format("%s becomes %s", inputStr, p.parse(inputStr)) );
			System.out.println(String.format("%.5f", c.calculate(p.parseInternal(inputStr))));
		} catch (RuntimeException ex) {
			System.err.println("Error in input expression: " + inputStr);
		}
	}
}
