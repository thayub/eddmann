package com.eddmann.blog.models

import com.tristanhunt.knockoff.DefaultDiscounter._
import com.tristanhunt.knockoff._
import scala.io.Source

case class Post(rawMeta: String, rawContent: String) {

  private val metaTemplate = """(?is)(.+):(.+)""".r

  lazy val meta = {
    (for {
      metaTemplate(key, value) <- rawMeta.split("\n")
    } yield key.trim -> value.trim).toMap.withDefaultValue("")
  }

  lazy val content = toXHTML(knockoff(rawContent))

}

object Post {

  private val PostTemplate = """(?is)-+\n(.+)\n-+\n+(.+)""".r

  def all(directory: String) = {
    (for {
      file <- (new java.io.File(directory)).listFiles.sortWith(_.getName > _.getName)
      if file.isFile
      if file.getName.endsWith(".markdown")
      PostTemplate(rawMeta, rawContent) <- List(Source.fromFile(file).mkString)
    } yield Post(rawMeta, rawContent)).toList
  }

  def findBySlug(directory: String, slug: String) =
    all(directory).find(_.meta("slug") == slug)

}