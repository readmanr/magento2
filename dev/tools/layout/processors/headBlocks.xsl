<!--
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an e-mail
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */
-->
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:php="http://php.net/xsl"
    xsl:extension-element-prefixes="php"
    exclude-result-prefixes="xsl php"
    >

    <!-- Copy nodes -->
    <xsl:template match="node()|@*">
        <xsl:copy>
            <xsl:apply-templates select="node()|@*" />
        </xsl:copy>
    </xsl:template>

    <xsl:template match="action[@method='addJs' or @method='addCss']">
        <block>
            <xsl:attribute name="class">
                <xsl:choose>
                    <xsl:when test="@method = 'addJs' ">Magento\Page\Block\Html\Head\Script</xsl:when>
                    <xsl:when test="@method = 'addCss'">Magento\Page\Block\Html\Head\Css</xsl:when>
                </xsl:choose>
            </xsl:attribute>
            <xsl:attribute name="name">
                <xsl:value-of select="php:function('strtolower', php:function('trim', php:function('preg_replace', '/[^a-z]+/i', '-', string(./*[1])), '-'))" />
            </xsl:attribute>
            <xsl:apply-templates select="@ifconfig" />
            <arguments>
                <argument name="file" xsi:type="string">
                    <xsl:value-of select="*[1]" />
                </argument>
                <xsl:if test="count(*[position() &gt; 1])">
                    <argument name="properties" xsi:type="array">
                        <xsl:if test="*[2]"><item name="attributes" xsi:type="array"><xsl:value-of select="*[2]" /></item></xsl:if>
                        <xsl:if test="*[3]"><item name="ie_condition" xsi:type="array"><xsl:value-of select="*[3]" /></item></xsl:if>
                        <xsl:if test="*[4]"><item name="flag_name" xsi:type="array"><xsl:value-of select="*[4]" /></item></xsl:if>
                    </argument>
                </xsl:if>
            </arguments>
        </block>
    </xsl:template>

    <xsl:template match="//reference[action[@method='removeItem']]">
        <xsl:copy>
            <xsl:apply-templates select="node()|@*" />
        </xsl:copy>
        <xsl:for-each select="action[@method='removeItem']">
            <remove name="{php:function('strtolower', php:function('trim', php:function('preg_replace', '/[^a-z]+/i', '-', string(*[2])), '-'))}" />
        </xsl:for-each>
      </xsl:template>

    <!-- Delete remove item call -->
    <xsl:template match="action[@method='removeItem']">
    </xsl:template>

</xsl:stylesheet>