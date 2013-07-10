package com.eddmann.blog

import org.scalatra._
import scalate.ScalateSupport
import org.fusesource.scalate.TemplateEngine
import org.fusesource.scalate.layout.DefaultLayoutStrategy
import com.eddmann.blog.models.{ Post => PostModel }
import java.io._

class BlogController extends ScalatraServlet with ScalateSupport {

  override protected def defaultTemplatePath: List[String] =
    List("/WEB-INF/templates/views")

  override protected def createTemplateEngine(config: ConfigT) = {
    val engine = super.createTemplateEngine(config)
    engine.layoutStrategy = new DefaultLayoutStrategy(engine,
      TemplateEngine.templateTypes.map("/WEB-INF/templates/layouts/default." + _): _*)
    engine.packagePrefix = "templates"
    engine
  }

  before() {
    contentType = "text/html"
  }

  get("/") {
    cache {
      ssp("index", "posts" -> PostModel.all("./posts"), "title" -> "edd mann")
    }
  }

  get("/posts/:slug/") {
    cache {
      val post = PostModel.findBySlug("./posts", params("slug"))

      post match {
        case Some(post) =>
          ssp("post", "post" -> post, "title" -> (post.meta("title") + " - edd mann"))
        case None => halt(404)
      }
    }
  }

  notFound {
    contentType = null
    serveStaticResource() getOrElse resourceNotFound()
  }

  private def cache(content: => String): String = {
    def md5(s: String) = {
      val instance = java.security.MessageDigest.getInstance("MD5")
      instance.digest(s.getBytes).map("%02x".format(_)).mkString
    }

    val file = new File("./cache/" + md5(request.getRequestURI))

    if (file.exists) {
      scala.io.Source.fromFile(file).mkString + "\n<!-- cached -->"
    } else {
      val output = content
      val writer = new PrintWriter(file)
      writer.write(output)
      writer.close
      output + "\n<!-- processed -->"
    }
  }
}