package ru.ivalera.opn;

import java.util.ArrayList;
import java.util.List;
import java.util.Stack;

public class Parser {
	enum CONSTS {
		E("E", Math.E), PI("PI", Math.PI);

		private String lexema;
		private Double value;

		public String getLexema() {
			return lexema;
		}

		public Double getValue() {
			return value;
		}

		private CONSTS(String lexema, Double value) {
			this.lexema = lexema;
			this.value = value;
		}

		@Override
		public String toString() {
			return getValue().toString();
		}
	}

	enum OPERATOR {
		LS("(", 0), PS(")", 0), ADD("+", 1), SUB("-", 1), MUL("*", 2), DIV("/",
				2);

		private String lexema;
		private Integer priority;

		private OPERATOR(String lexema, Integer priority) {
			this.lexema = lexema;
			this.priority = priority;
		}

		public String getLexema() {
			return lexema;
		}

		public Integer getPriority() {
			return priority;
		}

		@Override
		public String toString() {
			return getLexema();
		}

		public Object eval(Object pop, Object pop2) {
			if (pop != null && pop2 != null) {
				switch (this) {
				case ADD:
					return Double.parseDouble(pop.toString())
							+ Double.parseDouble(pop2.toString());
				case SUB:
					return Double.parseDouble(pop2.toString())
							- Double.parseDouble(pop.toString());
				case MUL:
					return Double.parseDouble(pop.toString())
							* Double.parseDouble(pop2.toString());
				case DIV:
					return Double.parseDouble(pop2.toString())
							/ Double.parseDouble(pop.toString());
				default:
					break;
				}
			}
			return null;
		}
	}

	enum FUNCTION {
		EXP("exp", 3), SIN("sin", 3), COS("cos", 3);

		private String lexema;
		private Integer priority;

		private FUNCTION(String lexema, Integer priority) {
			this.lexema = lexema;
			this.priority = priority;
		}

		public String getLexema() {
			return lexema;
		}

		public Integer getPriority() {
			return priority;
		}

		@Override
		public String toString() {
			return getLexema();
		}

		public Object eval(Object pop) {
			if (pop != null) {
				switch (this) {
				case COS:
					return Math.cos(Double.parseDouble(pop.toString()));
				case SIN:
					return Math.sin(Double.parseDouble(pop.toString()));
				case EXP:
					return Math.exp(Double.parseDouble(pop.toString()));
				}
			}
			return null;
		}
	}

	private FUNCTION checkFunction(String val) {
		for (FUNCTION f : FUNCTION.values()) {
			if (val.endsWith(f.getLexema()))
				return f;
		}
		return null;
	}

	private OPERATOR checkOperator(String val) {
		for (OPERATOR f : OPERATOR.values()) {
			if (val.endsWith(f.getLexema()))
				return f;
		}
		return null;
	}

	private CONSTS checkConst(String val) {
		for (CONSTS f : CONSTS.values()) {
			if (val.endsWith(f.getLexema()))
				return f;
		}
		return null;
	}

	private void clear(StringBuffer s) {
		s.delete(0, s.length());
	}

	public List<Object> parseInternal(String input) {
		Stack<Object> n = new Stack<Object>();
		List<Object> sb = new ArrayList<Object>();
		StringBuffer s = new StringBuffer();

		for (int i = 0; i < input.length(); ++i) {
			Character c = input.charAt(i);
			s.append(c);

			CONSTS cn = checkConst(s.toString());
			if (cn != null) {
				System.out.println("Found const " + cn);
				s.setLength(s.length() - cn.getLexema().length());
				if (s.length() > 0)
					sb.add(s.toString());
				clear(s);
				sb.add(cn.getValue());
			} else {
				FUNCTION f = checkFunction(s.toString());
				if (f != null) {
					System.out.println("Found function " + f);
					s.setLength(s.length() - f.getLexema().length());
					if (s.length() > 0)
						sb.add(s.toString());
					clear(s);
					n.push(f);
				} else {
					OPERATOR o = checkOperator(s.toString());
					if (o != null) {
						System.out.println("Found operator " + o);
						s.setLength(s.length() - o.getLexema().length());
						if (s.length() > 0)
							sb.add(s.toString());
						clear(s);
						if (o == OPERATOR.LS)
							n.push(o);
						else if (o == OPERATOR.PS) {
							while (!n.isEmpty() && n.peek() != OPERATOR.LS) {
								sb.add(n.pop());
							}
							n.pop();
							while (!n.isEmpty() && n.peek() instanceof FUNCTION) {
								sb.add(n.pop());
							}
						} else {
							while (!n.isEmpty()
									&& n.peek() instanceof OPERATOR
									&& o.getPriority() <= ((OPERATOR) n.peek())
											.getPriority()) {
								sb.add(n.pop());
							}
						}
						if (o != OPERATOR.LS && o != OPERATOR.PS) {
							n.push(o);
						}
					}
				}
				System.out.println("Stack " + n);
			}
		}

		if (s.length() > 0)
			sb.add(s.toString());
		
		while (!n.isEmpty()) {
			sb.add(n.pop());
		}

		return sb;
	}

	public String parse(String str) {
		return parseInternal(str).toString();
	}
}
