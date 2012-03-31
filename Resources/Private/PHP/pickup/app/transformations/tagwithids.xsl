<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:import href="identity.xsl" />
    <xsl:template match="node()[not(@id)]">
        <xsl:copy>
            <xsl:attribute name="id">
                <xsl:call-template name="generate-id" />
            </xsl:attribute>
            <xsl:apply-templates select="@*|node()"/>
        </xsl:copy>
    </xsl:template>

    <xsl:template name="generate-id-boo">
        <xsl:value-of select="generate-id(.)" />
    </xsl:template>

    <xsl:template name="generate-id">
        <xsl:value-of select="concat('pos',count(preceding::*))" />
    </xsl:template>

</xsl:stylesheet>