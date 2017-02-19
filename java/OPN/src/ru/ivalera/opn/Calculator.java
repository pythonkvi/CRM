package ru.ivalera.opn;

import java.util.List;
import java.util.Stack;

import ru.ivalera.opn.Parser.FUNCTION;
import ru.ivalera.opn.Parser.OPERATOR;

public class Calculator {
	public Double calculate(List<Object> opn) {
		Stack<Object> n = new Stack<Object>();
		for (Object obj : opn) {
			if (!(obj instanceof FUNCTION) && !(obj instanceof OPERATOR)) {
				n.push(obj);
			} else if (obj instanceof FUNCTION) {
				n.push(((FUNCTION)obj).eval(n.pop()));
			} else if (obj instanceof OPERATOR) {
				n.push(((OPERATOR)obj).eval(n.pop(), n.pop()));
			}
			System.out.println("Stack " + n);
		}
		if (!n.isEmpty()) {
			return Double.parseDouble(n.pop().toString());
		}
		return null;
	}
}
