package com.eddmann.blog

import org.scalatra._
import org.fusesource.scalate.TemplateEngine
import org.fusesource.scalate.layout.DefaultLayoutStrategy
import scalate.ScalateSupport
import java.io._
import com.eddmann.blog.model.{ Post => PostModel }

class BlogController extends ScalatraServlet with ScalateSupport {

  private val postsDirectory = "./posts"

  // private val postsDirectory = "/opt/jetty/webapps/root/posts"

  private val cacheDirectory = "./cache"

  // private val cacheDirectory = "/opt/jetty/webapps/root/cache"

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
      ssp("index", "posts" -> PostModel.allSplitBy(postsDirectory)(3), "title" -> "edd mann • software developer")
    }
  }

  get("/posts/:slug/?") {
    cache {
      val post = PostModel.findBySlug(postsDirectory, params("slug"))

      post match {
        case Some(post) =>
          ssp("post", "post" -> post, "title" -> (post.meta("title") + " • edd mann"))
        case None => halt(404)
      }
    }
  }

  get("/about/?") {
    cache {
      ssp("about", "title" -> "about • edd mann")
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

    val file = new File(cacheDirectory + "/" + md5(request.getRequestURI))

    if (file.exists) {
      scala.io.Source.fromFile(file).mkString
    } else {
      val output = content
      val writer = new PrintWriter(file)
      writer.write(output)
      writer.close
      output
    }
  }

}