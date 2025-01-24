CREATE DATABASE  IF NOT EXISTS `users` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `users`;
-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: users
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
INSERT INTO `admins` VALUES (1,'admin','$2y$10$68GWNPl8Lm2PdJdQPO8u0eAuMPDyYZVUegsoTzl0WQbOXTGyZowBy',NULL);
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ads` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `link` varchar(2083) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads`
--

LOCK TABLES `ads` WRITE;
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;
/*!40000 ALTER TABLE `ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `news_id` int NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `news_id` (`news_id`),
  CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `url` varchar(2083) NOT NULL,
  `url_to_image` text,
  `source_name` varchar(255) DEFAULT NULL,
  `published_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_url` (`url`(500))
) ENGINE=InnoDB AUTO_INCREMENT=1629 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'Polen und die Ukraine: \"Man hat uns nur wegen des Geldes aufgenommen\"','Zu Beginn des dritten Kriegswinters in der Ukraine ist die Hilfsbereitschaft der polnischen Nachbarn auf einem Tiefpunkt: Willkommenskultur weicht dem Konkurrenzdruck.','https://www.zeit.de/gesellschaft/zeitgeschehen/2024-12/polen-ukraine-krieg-hilfe-winter-konkurrenz','https://img.zeit.de/politik/ausland/2024-12/polen-ukraine-krieg-hilfe-winter-konkurrenz-bild/wide__1300x731','Die Zeit','2024-12-23 00:14:57','2024-12-23 21:11:49'),(2,'32yo Aussie teacher paraded in horror clip','WARNING: DistressingAn Australian teacher fighting for Ukraine’s foreign legion has been captured by Russian forces, with distressing footage showing him being interrogated and slapped, paraded online.','https://www.news.com.au/technology/innovation/military/who-the-f-are-you-horror-footage-emerges-of-australian-teacher-interrogated-by-russians/news-story/92a972fa7017b4b0eae3d742b6b13b62','https://content.api.news/v3/images/bin/22601375a4440c90b0ef510f837c2ce5','News.com.au','2024-12-22 20:29:00','2024-12-23 21:11:49'),(3,'Krieg in der Ukraine: Neue russische Drohnenschwärme über der Ukraine','Die russischen Reserven an Kampfdrohnen scheinen unerschöpflich. Wieder kreisen Schwärme der unbemannten Flugkörper am Himmel über der Ukraine.','https://www.tagesspiegel.de/internationales/krieg-in-der-ukraine-neue-russische-drohnenschwarme-uber-der-ukraine-12921097.html','https://www.tagesspiegel.de/images/12921094/alternates/BASE_16_9_W1400/1734898353000/immer-mehr-drohnenangriffe-in-der-ukraine.jpeg','Der Tagesspiegel','2024-12-22 23:12:33','2024-12-23 21:11:49'),(4,'Krieg in der Ukraine: Neue russische Drohnenschwärme über der Ukraine','Hier finden Sie Informationen zu dem Thema „Krieg in der Ukraine“. Lesen Sie jetzt „Neue russische Drohnenschwärme über der Ukraine“.','https://www.zeit.de/news/2024-12/22/neue-russische-drohnenschwaerme-ueber-der-ukraine','https://img.zeit.de/news/2024-12/22/neue-russische-drohnenschwaerme-ueber-der-ukraine-image-group/wide__1300x731','Die Zeit','2024-12-22 23:11:28','2024-12-23 21:11:49'),(5,'Niederlande empfehlen, Bargeld zu horten – was deutsche Banken dazu meinen','Ukrainekrieg, Cyberangriffe und durchtrennte Datenkabel: In den Niederlanden fordern Politik und Banken dazu a','https://t3n.de/news/niederlande-bargeld-deutsche-banken-1665161/','https://t3n.de/news/wp-content/uploads/2023/06/Taschengeld-bargeld-kleingeld-erziehung-finanzielle-bildung-shutterstock.jpg','T3n','2024-12-22 20:05:27','2024-12-23 21:11:49'),(6,'Spionfengselet','Her soner Ukrainas mest forhatte fanger.','https://www.nrk.no/urix/xl/ukrainere-som-spionerte-for-russland-har-et-eget-fengsel-1.17159141','https://gfx.nrk.no/XbfyW8h0mEQ7IxubGf9hSgUqMZuh8EX1nc3s4fEKAAOg.jpg','NRK','2024-12-22 20:00:00','2024-12-23 21:11:49'),(7,'Ukraine War: Trump Leaves Door Open to Putin’s Offer for Talks','President-elect Donald Trump suggested he’s open to meeting Vladimir Putin to discuss ending the war in Ukraine sparked by Russia’s 2022 invasion.','https://www.bloomberg.com/news/articles/2024-12-22/trump-leaves-door-open-to-putin-s-offer-tor-talks-on-ukraine-war','https://assets.bwbx.io/images/users/iqjWHBFdfxIU/i9.WRpofxyBA/v1/1200x800.jpg','Bloomberg','2024-12-22 21:46:39','2024-12-23 21:11:49'),(8,'Russia\'s Putin holds talks with Slovakian PM Fico, in a rare visit to Moscow by an EU leader','Russian President Vladimir Putin on Sunday hosted Slovakia’s prime minister, Robert Fico, in a rare visit to the Kremlin by an EU leader since Moscow\'s all-out invasion of Ukraine in February 2022.','https://apnews.com/45e08385f245bff9df02d6b533561598','https://storage.googleapis.com/afs-prod/media/9f9d06eebede49ac9f5948fac1c95bd5/1920.jpeg','Associated Press','2024-12-22 16:56:49','2024-12-23 21:11:49'),(9,'Putin drone attack hits apartment building','Russian President Vladimir Putin has vowed to bring more “destruction” to Ukraine in retaliation for a drone attack on the central Russian city of Kazan a day earlier.','https://www.news.com.au/world/europe/putin-vows-destruction-on-ukraine-after-kazan-drone-attack/news-story/e7f5705e6423521d75a7d232d07c9b16','https://content.api.news/v3/images/bin/381612011c8704a71a9b1fed24833c98','News.com.au','2024-12-22 16:25:00','2024-12-23 21:11:49'),(10,'Ukraine launches \'massive drone attack\' that hits apartment building in Kazan, Russian officials say','A drone smashes into a high-rise apartment building, damaging a skyscraper but leaving no victims, according to local officials.','https://www.abc.net.au/news/2024-12-22/ukraine-drone-attack-on-kazan-russia/104755442','https://live-production.wcms.abc-cdn.net.au/3576bfa5e6b296e409ac69fe9f8cb0c3?impolicy=wcms_watermark_news&cropH=483&cropW=858&xPos=0&yPos=20&width=862&height=485&imformat=generic','ABC News (AU)','2024-12-21 23:59:52','2024-12-23 21:11:50'),(11,'Ukraine says Russian general deliberately targeted Reuters staff in August missile strike','Ukraine\'s security service has named a Russian general it suspects of ordering a missile strike on a hotel in eastern Ukraine in August and said he acted \"with the motive of deliberately killing employees of\" Reuters.','https://www.reuters.com/world/china/ukraine-names-russian-general-suspect-missile-strike-that-killed-reuters-safety-2024-12-20/','https://www.reuters.com/resizer/v2/6KR7APUUXRIYXB3CMR7GGS342U.jpg?auth=9c02935a48b1bfd4b0028a7bf6b3227d07782c88d091fb2d655568e726f57639&height=1005&width=1920&quality=80&smart=true','Reuters','2024-12-20 15:01:39','2024-12-23 21:11:50'),(12,'Read the Full Transcript of Donald Trump’s 2024 Person of the Year Interview With TIME','Over the course of a wide-ranging interview, Trump discussed his election victory, the economy, and the situations in Ukraine and the Middle East.','http://time.com/7201565/person-of-the-year-2024-donald-trump-transcript/','https://api.time.com/wp-content/uploads/2024/12/time-person-of-year-trump-cover-transcript-2.jpg?quality=85&w=1200&h=628&crop=1','Time','2024-12-12 12:49:00','2024-12-23 21:11:50'),(13,'Zelensky alla folla a Vilnius, \'Nato più forte con Kiev\' - Ultima Ora','\"La Nato renderà l\'Ucraina più sicura e l\'Ucraina renderà la Nato più forte\". Così il presidente ucraino Volodymyr Zelensky ha arringato la folla radunata per la manifestazione #UkraineNATO33 a Vilnius, a margine del vertice Nato nella capitale lituana. (ANSA)','http://www.ansa.it/sito/notizie/topnews/2023/07/11/zelensky-alla-folla-a-vilnius-nato-piu-forte-con-kiev_b68e42d1-1e74-4abb-9d21-24e58812c468.html','https://www.ansa.it/webimages/img_700/2023/7/11/9d5a845d37563cd18e33607ecb671ab5.jpg','ANSA.it','2023-07-11 16:48:00','2024-12-23 21:11:50'),(14,'Bear Grylls Meets President Zelenskyy review: TV action man eager to set the world to rights','In this Channel 4 documentary, Grylls is very good at being earnest and is genuinely moved by the fortitude of Ukraine','https://www.irishtimes.com/culture/tv-radio/2023/03/29/bear-grylls-meets-president-zelenskyy-review-tv-action-man-eager-to-set-the-world-to-rights/','https://www.irishtimes.com/resizer/6p88mds4ABkJsWVTO8rznsDX2_g=/1200x630/filters:format(jpg):quality(70):focal(1350x510:1360x520)/cloudfront-eu-central-1.images.arcpublishing.com/irishtimes/67CBWCZ4IRHTBOYE6PRS4STLFU.jpg','The Irish Times','2023-03-29 12:56:56','2024-12-23 21:11:50'),(15,'Leaders of Germany, France, Italy, Romania in Ukraine to support fight against Russia','Zelensky expected to push for more arms to withstand the Russian invaders after accusing France, Germany and Italy of foot-dragging','https://www.theglobeandmail.com/world/article-leaders-of-germany-france-italy-romania-in-ukraine-to-support-fight/','https://www.theglobeandmail.com/resizer/6iggvDIIPm8nMfFIyizxVooISQk=/1200x801/filters:quality(80)/cloudfront-us-east-1.images.arcpublishing.com/tgam/7DA5SGPPCFK5JE7H2QYZYJMDYQ.jpg','The Globe And Mail','2022-06-16 10:05:33','2024-12-23 21:11:50'),(16,'Partying with the Russians: the message is, this will all blow over some day','By sending a representative, Global Affairs betrayed its attitude toward the war in Ukraine','https://www.theglobeandmail.com/opinion/article-partying-with-the-russians-the-message-is-this-will-all-blow-over-some/','https://www.theglobeandmail.com/resizer/R3Y_DNOEovI5S-QHfuvtEcm2plk=/1200x784/filters:quality(80)/cloudfront-us-east-1.images.arcpublishing.com/tgam/Q2UEI7OMKVN4TFSD2YFLLCSVLQ.JPG','The Globe And Mail','2022-06-15 12:00:00','2024-12-23 21:11:50'),(247,'Russia’s Putin holds talks with Slovakian PM Fico, in a rare visit to Moscow by an EU leader','Russian President Vladimir Putin on Sunday hosted Slovakia’s prime minister, Robert Fico, in a rare visit to the Kremlin by an EU leader since Moscow’s all-out invasion of Ukraine in February 2022.','https://www.cnn.com/2024/12/22/europe/russias-putin-talks-with-slovakian-pm-latam/index.html','https://media.cnn.com/api/v1/images/stellar/prod/2024-12-22t172741z-77962637-rc2hubaxafat-rtrmadp-3-ukraine-crisis-russia-slovakia.JPG?c=16x9&q=w_800,c_fill','CNN','2024-12-22 21:59:14','2024-12-23 22:01:16'),(261,'„Führt und weg von Europa“: Slowakischer Regierungschef Fico bei Putin im Kreml – Opposition empört','Putin droht der Ukraine mit einem „Vielfachen der Zerstörungen“ + EU will offenbar im Februar neues Sanktionspaket gegen Moskau verabschieden + Der Newsblog.','https://www.tagesspiegel.de/internationales/liveblog/fuhrt-und-weg-von-europa-slowakischer-regierungschef-fico-bei-putin-im-kreml--opposition-emport-4309180.html','https://www.tagesspiegel.de/images/12921154/alternates/BASE_16_9_W1400/1734905024000/fico-zu-gesprachen-mit-putin-im-kreml.jpeg','Der Tagesspiegel','2022-07-23 16:09:46','2024-12-23 22:01:17'),(485,'Russia Ukraine war: Australian man identified as Oscar Jenkins captured by Russian forces near Donbas','Anthony Albanese is urgently seeking information about a Melbourne man believed to have been captured while fighting as a mercenary in Ukraine.','http://www.afr.com/politics/diplomatic-scramble-after-australian-captured-in-ukraine-by-russians-20241223-p5l0b8','https://static.ffx.io/images/$zoom_1.1755%2C$multiply_1%2C$ratio_1.777778%2C$width_1059%2C$x_0%2C$y_216/t_crop_custom/c_scale%2Cw_800%2Cq_88%2Cf_jpg/t_afr_no_label_no_age_social_wm/4cd27c324a95642926c3011704d15661a0155c99','Australian Financial Review','2024-12-22 23:19:48','2024-12-23 23:46:46'),(486,'Slovakia\'s Robert Fico meets Vladimir Putin in surprise Moscow visit','Robert Fico said he and the Russian leader discussed \"the possibilities of an early, peaceful end of the war\" in Ukraine.','https://www.bbc.co.uk/news/articles/cz0rn85v5kjo','https://ichef.bbci.co.uk/ace/branded_news/1200/cpsprodpb/7af6/live/2eef13b0-c0ad-11ef-8baa-25c112b5be62.png','BBC News','2024-12-22 22:52:25','2024-12-23 23:46:46'),(487,'Russia captures two villages in Ukraine as Moscow\'s forces advance on two cities','Russian forces captured two villages in Ukraine, one in Kharkiv region in the northeast and one in eastern Donetsk region, the Russian Defence Ministry said on Sunday.','https://www.reuters.com/world/europe/russia-captures-two-villages-ukraine-moscows-forces-advance-two-cities-2024-12-22/','https://www.reuters.com/resizer/v2/466BJJQ7PVGY5O53NZ3KL65MHM.png?auth=b9c3bf166c40a6778eb5672993fde7c30a15f48329026674eff92afd8da1d0ca&height=1005&width=1920&quality=80&smart=true','Reuters','2024-12-22 22:00:32','2024-12-23 23:46:46'),(500,'„Führt uns weg von Europa“: Slowakischer Regierungschef Fico bei Putin im Kreml – Opposition empört','Putin droht der Ukraine mit einem „Vielfachen der Zerstörungen“ + EU will offenbar im Februar neues Sanktionspaket gegen Moskau verabschieden + Der Newsblog.','https://www.tagesspiegel.de/internationales/liveblog/fuhrt-uns-weg-von-europa-slowakischer-regierungschef-fico-bei-putin-im-kreml--opposition-emport-4309180.html','https://www.tagesspiegel.de/images/12921154/alternates/BASE_16_9_W1400/1734905024000/fico-zu-gesprachen-mit-putin-im-kreml.jpeg','Der Tagesspiegel','2022-07-23 16:09:46','2024-12-23 23:46:50'),(637,'Fareed’s take: Internationally, Biden leaves Trump with plenty of opportunities | CNN Politics','President-elect Donald Trump will return to the White House with a suite of foreign-policy problems to face, from Ukraine to the Middle East. But Fareed argues that President Joe Biden leaves Trump with a wealth of geopolitical opportunities, as America’s top…','https://www.cnn.com/2024/12/22/politics/video/gps1222-bidens-foreign-policy-accomplishments','https://media.cnn.com/api/v1/images/stellar/videothumbnails/85537699-13594005-generated-thumbnail.jpg?c=16x9&q=w_800,c_fill','CNN','2024-12-22 18:00:45','2024-12-23 23:56:02'),(1034,'Five months on, Ukraine clings to Kursk amid doubts over operation','Outnumbered Ukrainian troops are clinging to a section of Russian territory in hopes of using it as a bargaining chip in any future negotiations.','https://www.washingtonpost.com/world/2024/12/22/ukraine-russia-kursk-counterattack/','https://www.washingtonpost.com/wp-apps/imrs.php?src=https://arc-anglerfish-washpost-prod-washpost.s3.amazonaws.com/public/Z33CZXSJTIBSZU6CW3Y3ITIZVM.jpg&w=1440','The Washington Post','2024-12-22 07:00:40','2024-12-24 00:13:17'),(1161,'Russian social media video appears to show Australian man captured in Ukraine','Australian officials have launched an urgent investigation after a video emerged on social media that appeared to show an Australian man being questioned and slapped by Russian fighters.','https://www.abc.net.au/news/2024-12-23/australian-captured-russian-fighters-ukraine-social-media-video/104757084','https://live-production.wcms.abc-cdn.net.au/2a0f2c84ac7c1d04d83a4dbedcbf9f1b?impolicy=wcms_watermark_news&cropH=252&cropW=448&xPos=10&yPos=229&width=862&height=485&imformat=generic','ABC News (AU)','2024-12-23 00:32:07','2024-12-24 00:24:46'),(1341,'Satellite Images Show North Korea Boosting Arms Flow to Russia','Pyongyang is producing and sending more arms to Russia for its war in Ukraine, deepening their alliance and giving Moscow more battlefield firepower','https://www.wsj.com/world/russia-north-korea-weapons-shipment-676d7f52?mod=hp_lead_pos8','https://images.wsj.net/im-63312602/social','The Wall Street Journal','2024-12-23 04:00:00','2024-12-24 05:22:34'),(1419,'After years of war, 6.8 million Ukrainian refugees’ lives are still mired in uncertainty','Her home engulfed by war, Ukrainian mother Yana Felos found herself in Britain with no friends and no community. Now, she says she has nothing to return to in Ukraine.','https://www.cnn.com/2024/12/23/europe/ukraine-refugees-uncertainty-war-intl/index.html','https://media.cnn.com/api/v1/images/stellar/prod/gettyimages-1384228489.jpg?c=16x9&q=w_800,c_fill','CNN','2024-12-23 05:01:42','2024-12-24 05:27:44'),(1420,'Detained Aussie’s wild vegan rant','Bizarre video of an Australian man - now believed to be captive in the hands of Russian soldiers in Ukraine - has emerged of him ranting about veganism and wanting to “force” Chinese people to be vegan.','https://www.news.com.au/technology/online/social/youre-gonna-be-vegan-soon-bizarre-youtube-rant-from-aussie-captured-by-russians/news-story/812bdfe4bb6b9f0f9688793095adec71','https://content.api.news/v3/images/bin/06f77258297498a6d794ed00f5414a2c','News.com.au','2024-12-23 04:25:00','2024-12-24 05:27:44'),(1551,'„Führt uns weg von Europa“: Slowakischer Regierungschef Fico bei Putin im Kreml – Opposition empört','Überraschungsvisite im Kreml: Der slowakische Ministerpräsident trifft in Moskau jenen Mann, der den Angriffskrieg gegen die Ukraine befohlen hat. Die Opposition in dem EU- und Nato-Land ist entsetzt.','https://www.tagesspiegel.de/internationales/fuhrt-uns-weg-von-europa-slowakischer-regierungschef-fico-bei-putin-im-kreml--opposition-emport-12921502.html','https://www.tagesspiegel.de/images/12921517/alternates/BASE_16_9_W1400/1734936235000/fico-zu-gesprachen-mit-putin-im-kreml.jpeg','Der Tagesspiegel','2024-12-23 09:54:17','2024-12-24 06:58:47'),(1556,'Die Ukraine hat noch so wenige Front-Soldaten, dass Generalstab schweren Schritt geht','Die Armee Kiews hat große Probleme, ihre Lücken aufzufüllen. Der Generalstab versucht einem Bericht zufolge, die ersten Linien durch Versetzungen zu verstärken. Die Militärs weisen dies zurück.','https://www.focus.de/politik/ausland/die-persona-in-ukrainischer-armee-ist-so-knapp-dass-generalstab-schweren-schritt-geht_51201f01-0cb1-47d2-8808-b817df1f3d70.html','https://quadro.burda-forward.de/ctf/e9edf216-c9c6-452b-ade6-cfe6b50abfb7.e43467bd-2c7a-4064-8413-d4eb96fc8e1f.png?im=RegionOfInterestCrop%3D%281200%2C630%29%2CregionOfInterest%3D%28685%2C637%29&hash=e1347ec58e86d5a4a12bbde4e8a007bec0e1c532cd41350fbab8341ffe915a77','Focus','2024-12-22 16:44:00','2024-12-24 06:58:49'),(1561,'Bericht des südkoreanischen Generalstabs: Offenbar 1100 nordkoreanische Soldaten im Ukraine-Krieg getötet oder verletzt','Slowakischer Regierungschef Fico bei Putin im Kreml – Opposition empört + Putin droht der Ukraine mit einem „Vielfachen der Zerstörungen“ + Der Newsblog.','https://www.tagesspiegel.de/internationales/liveblog/bericht-des-sudkoreanischen-generalstabs-offenbar-1100-nordkoreanische-soldaten-im-ukraine-krieg-getotet-oder-verletzt-4309180.html','https://www.tagesspiegel.de/images/12644150/alternates/BASE_16_9_W1400/1734605672000/nordkorea-russland-junge-soldaten.jpeg','Der Tagesspiegel','2022-07-23 16:09:46','2024-12-24 06:58:49');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'user','user','user@gmail.com','$2y$10$WdNZ0QSrCzMbYsqwZL7XCuVjOb8.TXV.8vmhUs/tRKhMYzjjT1DfS'),(4,'Trying','try','trying@gmail.com','$2y$10$TKjVARKH9HSrF8darEKZq.3dmQXmWoUXYth555c3Qfpu5D1g7w4/.');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-24 12:07:17
