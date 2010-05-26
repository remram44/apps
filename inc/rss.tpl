<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
  <channel>
    <link>{CHANNEL_LIEN}</link>
    <language>fr-fr</language>
    <title>{TITRE}</title>
    <description>Dernières demandes sur {CHANNEL_LIEN}</description>
<!-- BEGIN DEMANDE -->
    <item>
      <title>{DEMANDE.TITRE} ({DEMANDE.STATUT})</title>
      <guid isPermaLink="true">{DEMANDE.LIEN}</guid>
      <description>{DEMANDE.DESCRIPTION}</description>
      <author>{DEMANDE.AUT_NOM} ({DEMANDE.AUT_PSEUDO}, {DEMANDE.AUT_PROMO})</author>
      <pubDate>{DEMANDE.DATE_CREATION}</pubDate>
      <link>{CHANNEL_LIEN}index.php?mod=demande&amp;id={DEMANDE.ID}</link>
    </item>
<!-- END DEMANDE -->
  </channel>
</rss>
