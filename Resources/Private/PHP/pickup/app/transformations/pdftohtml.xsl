<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output
        method="xml"
        indent="yes"
        encoding="utf-8"
        doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
        doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" />

    <xsl:param name="font-family-override" select="'Arial'" />

    <xsl:template match="pdf2xml">
        <html id="{@id}">            
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <title>pdftohtml as xml to html</title>
                <style type="text/css">
                    body {
                        background-color:#efefef;
                        font-size:12px;
                    }
                    .page {
                        position:relative;
                        background-color:#fff;
                        -moz-box-shadow: 3px 1px 5px #666;
                        -webkit-box-shadow: 3px 1px 5px #666;
                        box-shadow: 3px 1px 5px #666;
                        margin:0 10px 10px 0;
                    }

                    .pagenr {
                        position:absolute;
                        top:10px;
                        right:10px;
                        color:#999;
                    }
                    .text {
                        display:block;
                        position:absolute;
                        background-color:#eee;
                    }
                    <xsl:apply-templates select="page/fontspec" mode="style" />
                </style>
            </head>
            <body>
                <xsl:apply-templates select="*" />
            </body>
        </html>
    </xsl:template>

    <xsl:template match="fontspec" mode="style">
        <xsl:text>
            .class</xsl:text><xsl:value-of select="@id" /><xsl:text> {
                font-family:</xsl:text><xsl:call-template name="font-family" /><xsl:text>;
                color:</xsl:text><xsl:value-of select="@color" /><xsl:text>;
                font-size:</xsl:text><xsl:value-of select="@size div 10" />em;<xsl:text>;
            }
        </xsl:text>
    </xsl:template>


    <xsl:template name="font-family">
        <xsl:choose>
            <xsl:when test="$font-family-override"><xsl:value-of select="$font-family-override" /></xsl:when>
            <xsl:otherwise><xsl:value-of select="@family" /></xsl:otherwise>
        </xsl:choose>        
    </xsl:template>

    <xsl:template match="page">
        <div class="page" id="{@id}" style="width:{@width}px;height:{@height}px;background-image:url({@backgroundrows});">
            <div style="width:{@width}px;height:{@height}px;background-image:url({@backgroundcols});">
                <p class="pagenr">#<xsl:value-of select="@number"/></p>
                <xsl:apply-templates />
            </div>
        </div>
    </xsl:template>

    <xsl:template match="text">
        <span class="class{@font} text" id="{@id}" style="top:{@top}px;left:{@left}px;width:{@width}px;height:{@height}px;">
           <xsl:apply-templates />
        </span>
    </xsl:template>
</xsl:stylesheet>