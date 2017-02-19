package testJSON;

import javax.ws.rs.GET;
import javax.ws.rs.Path;
import javax.ws.rs.Produces;
import javax.ws.rs.QueryParam;
import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlRootElement;

@Path("/api")
public class HelloService {
	@GET
	@Path("echo")
	@Produces("application/json")
	public TestBean echo(@QueryParam("q") String original) {
		return new TestBean(original);
	}
	
	@XmlRootElement
	public class TestBean {
		@XmlElement(name="m")
		private String message;

		public TestBean(String message) {
			super();
			this.message = message;
		}
		
		public void setMessage(String message) {
			this.message = message;
		}
		
		public String getMessage() {
			return message;
		}
	}
}
