package com.eddmann.blog.models

import com.tristanhunt.knockoff.DefaultDiscounter._
import com.tristanhunt.knockoff._
import scala.io.Source

case class PostModel(rawMeta: String, rawContent: String) {

  private val metaTemplate = """(?is)(.+):(.+)""".r

  lazy val meta = {
    (for {
      metaTemplate(key, value) <- rawMeta.split("\n")
    } yield key.trim -> value.trim).toMap
  }

  lazy val content = {
    toXHTML(knockoff(rawContent))
  }

}

object PostModel {

  private val PostTemplate = """(?is)-+\n(.+)\n-+\n+(.+)""".r

  def all = {
    (for {
      file <- (new java.io.File("./posts")).listFiles.sortWith(_.getName > _.getName)
      if file.isFile
      if file.getName.endsWith(".markdown")
      PostTemplate(rawMeta, rawContent) <- List(Source.fromFile(file).mkString)
    } yield PostModel(rawMeta, rawContent)).toList
  }

}