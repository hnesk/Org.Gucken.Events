<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform"  version="1.0">
<xsl:output method="xml" indent="yes" encoding="utf-8" doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"/>
<xsl:param name="baseUri" />
<xsl:param name="keep.class" select="1" />
<xsl:param name="keep.div" select="1" />

<xsl:template match="/">
	<div>
		<xsl:apply-templates select="*|node()"/>
	</div>
</xsl:template>

<xsl:template match="h1|h2|h3|h4|h5|h6|p|br|hr|a|ul|ol|li|dl|dd|dt|blockquote|cite|q|pre|code|address|em|strong|abbr|acronym|del|ins|strike|dfn">
	<xsl:copy>
		<xsl:apply-templates select="@*|node()"/>
	</xsl:copy>
</xsl:template>


<xsl:template match="text()|@href|@cite|@title|@dir|@lang">
	<xsl:copy />
</xsl:template>


<xsl:template match="@href">
	<xsl:attribute name="href">
		<xsl:choose>
			<xsl:when test="starts-with(.,'http://') or starts-with(.,'https://') or starts-with(.,'ftp://') ">
				<xsl:value-of select="." />
			</xsl:when>
			<xsl:otherwise>
				<xsl:value-of select="concat($baseUri,.)" />
			</xsl:otherwise>
		</xsl:choose>		
	</xsl:attribute>
</xsl:template>

<xsl:template match="p[string-length(normalize-space(.)) = 0]" />
<xsl:template match="a[string-length(normalize-space(.)) = 0]" />
<xsl:template match="li[string-length(normalize-space(.)) = 0]" />
<xsl:template match="ul[not(li)]|ol[not(li)]|dl[not(dd|dt)]" />


<xsl:template match="@*|node()">
	<xsl:apply-templates select="*|node()"/>
</xsl:template>


<!-- optional tags -->
<xsl:template match="div">
        <xsl:choose>
            <xsl:when test="$keep.div">
                <xsl:copy>
                        <xsl:apply-templates select="@*|node()"/>
                </xsl:copy>
            </xsl:when>
            <xsl:otherwise>
                <xsl:apply-templates select="node()"/>
            </xsl:otherwise>
        </xsl:choose>
</xsl:template>

<!-- optional attributes -->
<xsl:template match="@class">
    <xsl:if test="$keep.class">
        <xsl:copy />
    </xsl:if>
</xsl:template>



</xsl:stylesheet>