<?php
	
	$myfile = fopen("exception/SNExpectedException.java", "w") or die("Unable to open file!");
	//$myfile = fopen($mainPaqueteria_route."/".$sng->name."_1_TransformIn_".$sng->dependencia.".java", "w") or die("Unable to open file!");


	$txt = 'package '.$sng->paqueteria.'.exception;

import static org.hamcrest.CoreMatchers.instanceOf;
import static org.junit.matchers.JUnitMatchers.both;
import static org.junit.matchers.JUnitMatchers.containsString;

import org.apache.camel.CamelExecutionException;
import org.hamcrest.Description;
import org.hamcrest.Matcher;
import org.hamcrest.StringDescription;
import org.junit.Assert;
import org.junit.internal.matchers.TypeSafeMatcher;
import org.junit.rules.MethodRule;
import org.junit.runners.model.FrameworkMethod;
import org.junit.runners.model.Statement;

/**
 * The ExpectedException Rule allows in-test specification of expected exception
 * types and messages:
 * 
 * <pre>
 * // These tests all pass.
 * public static class HasExpectedException {
 * 	&#064;Rule
 * 	public ExpectedException thrown = new ExpectedException();
 * 
 * 	&#064;Test
 * 	public void throwsNothing() {
 * 		// no exception expected, none thrown: passes.
 * 	}
 * 
 * 	&#064;Test
 * 	public void throwsNullPointerException() {
 * 		thrown.expect(NullPointerException.class);
 * 		throw new NullPointerException();
 * 	}
 * 
 * 	&#064;Test
 * 	public void throwsNullPointerExceptionWithMessage() {
 * 		thrown.expect(NullPointerException.class);
 * 		thrown.expectMessage(&quot;happened?&quot;);
 * 		thrown.expectMessage(startsWith(&quot;What&quot;));
 * 		throw new NullPointerException(&quot;What happened?&quot;);
 * 	}
 * }
 * </pre>
 */
public class SNExpectedException implements MethodRule {
	/**
	 * @return a Rule that expects no exception to be thrown (identical to
	 *         behavior without this Rule)
	 */
	public static SNExpectedException none() {
		return new SNExpectedException();
	}

	private Matcher<Object> fMatcher = null;

	private SNExpectedException() {
	}

	public Statement apply(final Statement base, final FrameworkMethod method,
			final Object target) {
		return new ExpectedExceptionStatement(base);
	}

	/**
	 * Adds {@code matcher} to the list of requirements for any thrown
	 * exception.
	 */
	// Should be able to remove this suppression in some brave new hamcrest
	// world.
	@SuppressWarnings("unchecked")
	public void expect(final Matcher<?> matcher) {
		if (fMatcher == null) {
			fMatcher = (Matcher<Object>) matcher;
		} else {
			fMatcher = both(fMatcher).and(matcher);
		}
	}

	/**
	 * Adds to the list of requirements for any thrown exception that it should
	 * be an instance of {@code type}
	 */
	public void expect(final Class<? extends Throwable> type) {
		expect(instanceOf(type));
	}

	/**
	 * Adds to the list of requirements for any thrown exception that it should
	 * <em>contain</em> string {@code substring}
	 */
	public void expectMessage(final String substring) {
		expectMessage(containsString(substring));
	}

	/**
	 * Adds {@code matcher} to the list of requirements for the message returned
	 * from any thrown exception.
	 */
	public void expectMessage(final Matcher<String> matcher) {
		expect(hasMessage(matcher));
	}

	private class ExpectedExceptionStatement extends Statement {
		private final Statement fNext;

		public ExpectedExceptionStatement(final Statement base) {
			fNext = base;
		}

		@Override
		public void evaluate() throws Throwable {
			try {
				fNext.evaluate();
			} catch (Throwable e) {
				if (fMatcher == null) {
					throw e;
				}
				if (e instanceof CamelExecutionException) {
					e = e.getCause() == null ? e : e.getCause();
				}
				Assert.assertThat(e, fMatcher);
				return;
			}
			if (fMatcher != null) {
				throw new AssertionError("Expected test to throw "
						+ StringDescription.toString(fMatcher));
			}
		}
	}

	private Matcher<Throwable> hasMessage(final Matcher<String> matcher) {
		return new TypeSafeMatcher<Throwable>() {
			public void describeTo(final Description description) {
				description.appendText("exception with message ");
				description.appendDescriptionOf(matcher);
			}

			@Override
			public boolean matchesSafely(final Throwable item) {
				return matcher.matches(item.getMessage());
			}
		};
	}

	public void tieneCodigoError(final String errorCode) {
		this.expect(new SNExceptionMatcher(errorCode));
	}

	public void tieneCodigoErrorYMensajeContiene(final String errorCode,
			final String contiene) {
		this.expect(new SNExceptionMatcher(errorCode, contiene));
	}

}';


	if (fwrite($myfile, $txt))
		echo true;
	else
		echo false;

	fclose($myfile);

?>