-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 28, 2020 at 04:06 PM
-- Server version: 10.2.27-MariaDB-cll-lve
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `senstable`
--

-- --------------------------------------------------------

--
-- Table structure for table `sensors`
--

CREATE TABLE `sensors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `short_description` varchar(200) DEFAULT NULL,
  `serial_number` varchar(100) DEFAULT NULL,
  `diagram` varchar(100) DEFAULT NULL,
  `wiki` longtext DEFAULT NULL,
  `code` longtext DEFAULT NULL,
  `date_added` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `sensors`
--

INSERT INTO `sensors` (`id`, `name`, `short_description`, `serial_number`, `diagram`, `wiki`, `code`, `date_added`) VALUES
(3, 'Ultrasoon sensor', 'Sensor die afstand kan bepalen door middel van ultrasone geluidsgolven', 'HC-SR04', 'img/sensors/hc-sr04.png', 'Het werkt als volgt, op de triggerpin moet voor 10 us een hoog signaal staan, daarna gaat het piezzo element een signaal uitzenden van 8Ã—40 kHz.\nNadat het signaal is uitgezonden, gaat het 2e piezzo element â€œluisterenâ€ naar het uitgezonden signaal (pin echo), met de tijd daartussen kan de afstand bepaald worden.', '#define trigPin 4\n#define echoPin 2\n\n// Define variables:\nlong duration;\nint distance;\n\nvoid setup() {\n  // Define inputs and outputs:\n  pinMode(trigPin, OUTPUT);\n  pinMode(echoPin, INPUT);\n  \n  //Begin Serial communication at a baudrate of 9600:\n  Serial.begin(9600);\n}\nvoid loop() {\n  // Clear the trigPin by setting it LOW:\n  digitalWrite(trigPin, LOW);\n  delayMicroseconds(5);\n  \n  // Trigger the sensor by setting the trigPin high for 10 microseconds:\n  digitalWrite(trigPin, HIGH);\n  delayMicroseconds(10);\n  digitalWrite(trigPin, LOW);\n  \n  // Read the echoPin, pulseIn() returns the duration (length of the pulse) in microseconds:\n  duration = pulseIn(echoPin, HIGH);\n  \n  // Calculate the distance:\n  distance = duration * 0.034 / 2;\n  \n  // Print the distance on the Serial Monitor (Ctrl+Shift+M):\n  Serial.print(\"Distance = \");\n  Serial.print(distance);\n  Serial.println(\" cm\");\n\n  delay(50);\n}', '2020-03-23'),
(5, 'Luchtvochtigheid en temperatuur sensor', 'De DHT11 is een eenvoudige, low-cost digitale temperatuur- en vochtigheidssensor.', 'DHT11', 'img/sensors/dht11.png', 'De DHT-11 is een digitale luchtvochtigheids- en temperatuurmeter.  Voor het uitlezen van de sensor gebruiken we de volgende bibliotheek: https://github.com/adafruit/DHT-sensor-library\n\nDeze bibliotheek doet het lastige programmer werk voor je, waardoor de temperatuur en de luchtvochtigheid kunnen worden uitgelezen met de aanroep van een functie. De functie geeft een temperatuur (in graden Celsius of in Fahrenheit) of luchtvochtigheid terug en deze kan je dan in een variable stoppen. \nDe DHT11 kan temperaturen tussen de 0 en 50 graden Celcius meten met een nauwkeurigheid van 2 graden en luchtvochtigheid tussen de 20 en 80% met een nauwkeurigheid van 5%.  \n\nDe DHT22 kan temperaturen tussen de -40 en 80 graden Celcius meten met een nauwkeurigheid van 0.5 graden en luchtvochtigheid tussen de 0 en 100% met een nauwkeurigheid van 2-5%. \n', '#include \"DHT.h\"\n\n#define DHTPIN 7     // what digital pin we\'re connected to\n\n\n//uncomment whatever type you\'re using \n#define DHTTYPE DHT11   // DHT 11\n//#define DHTTYPE DHT22   // DHT 22\n\nDHT dht(DHTPIN, DHTTYPE);\n\nvoid setup() {\n  Serial.begin(9600);\n  Serial.println(\"DHTxx test!\");\n\n  dht.begin();\n}\n\nvoid loop() {\n  // Wait a few seconds between measurements.\n  delay(2000);\n\n  // Reading temperature or humidity takes about 250 milliseconds!\n  // Sensor readings may also be up to 2 seconds \'old\' (its a very slow sensor)\n  float h = dht.readHumidity();\n  // Read temperature as Celsius (the default)\n  float t = dht.readTemperature();\n  // Read temperature as Fahrenheit (isFahrenheit = true)\n  float f = dht.readTemperature(true);\n\n  // Check if any reads failed and exit early (to try again).\n  if (isnan(h) || isnan(t) || isnan(f)) {\n    Serial.println(\"Failed to read from DHT sensor!\");\n    return;\n  }\n\n  // Compute heat index in Fahrenheit (the default)\n  float hif = dht.computeHeatIndex(f, h);\n  // Compute heat index in Celsius (isFahreheit = false)\n  float hic = dht.computeHeatIndex(t, h, false);\n\n  Serial.print(\"Humidity: \");\n  Serial.print(h);\n  Serial.print(\" %\\t\");\n  Serial.print(\"Temperature: \");\n  Serial.print(t);\n  Serial.print(\" *C \");\n  Serial.print(f);\n  Serial.print(\" *F\\t\");\n  Serial.print(\"Heat index: \");\n  Serial.print(hic);\n  Serial.print(\" *C \");\n  Serial.print(hif);\n  Serial.println(\" *F\");\n}', '2020-04-03'),
(6, 'LDR sensor ', 'De lichtgevoelige weerstand is de weerstand van een halfgeleidermateriaal; de geleidbaarheid verande', 'GL5516', 'img/sensors/gl5516.PNG', 'De weerstand van de LDR is afhankelijk van de hoeveelheid licht. De afkorting LDR betekent light dependent resistor. In het donker is de weerstand erg groot. Dan kan het wel tot 10 000 000 ohm. Als er fel licht op de LDR valt, is de weerstand kleiner. (100 ohm, afhankelijk van het type LDR)\nEen LDR kan je gebruiken als sensor in een lichtsterktemeter. Hoe meer licht er op de LDR valt:\nâ€“ des te kleiner de weerstand van de LDR\nâ€“ en des te groter is de stroomsterkte van de LDR.Om de LDR uit te lezen heb je een vaste weerstand nodig (bijvoorbeeld 220 ohm of 1K ohm), de weerstand functioneert dan als een spanningsdeler, zo kun je de spanning aflezen via de analoge uitgang (waarde 5v = 1024, waarde 0v = 0)\n', 'int sensorPin = A0;    // select the input pin for the potentiometer\nint sensorValue = 0;  // variable to store the value coming from the sensor\n\nvoid setup() {\n  // declare the ledPin as an OUTPUT:\n  pinMode(sensorPin, INPUT);\n  Serial.begin(9600);\n}\n\nvoid loop() {\n  // read the value from the sensor:\n  sensorValue = analogRead(sensorPin);\n  // print the measured value\n  Serial.println(sensorValue);\n  delay(500);\n}', '2020-04-03'),
(7, 'Photo interrupt sensor', 'De KY-010 is een photo interrupt sensor. Deze detecteert of er iets de laser onderbreekt tussen het ', 'KY-010', 'img/sensors/ky-010.PNG', '0', 'int LBSPin = 3;  // input pin\nint Obstakel = HIGH;  // HIGH betekent geen detectie\nvoid setup() \n{\n pinMode(LBSPin, INPUT);\n Serial.begin(9600);\n}\nvoid loop() \n{\n Obstakel = digitalRead(LBSPin);\n if (Obstakel == LOW)\n {\n   Serial.println(\"Voorwerp gedetecteerd\");  \n }\n else\n {\n   Serial.println(\"clear\");\n }\n delay(200);\n}\n', '2020-04-03'),
(8, 'Reed switch', 'Een Reed Switch is een kleine elektrische schakelaar die wordt bediend door een magneet', 'KY-025 ', 'img/sensors/ky-025.PNG', 'Over het algemeen is de reed switch een elektrische schakelaar, die wordt bediend door een aangelegd magnetisch veld. Het bestaat uit een gesloten glazen buisje gevuld met rhodium gas en twee reeds. Wanneer een magnetische substantie, bijvoorbeeld een magneet het glazen buisje nadert, zullen de reeds samenkomen vanwege het magnetisch veld en zo ontstaat er een elektrisch circuit.\n', 'int Switch = 2;  //Reed Switch\nint Led    = 13;  // LED\nvoid setup()\n{\n pinMode(Led, OUTPUT);\n pinMode(Switch, INPUT);\n Serial.begin(9600);\n}\nvoid loop()\n{\n int field = digitalRead(Switch); // Read Digital value from switch\n if (field == LOW)\n {\n   digitalWrite(Led, HIGH); // LED-----> AAN\n   Serial.println(\"Magnetisch veld gedetecteerd\");\n }\n else\n {\n   digitalWrite(Led, LOW); // LED-----> UIT\n   Serial.println(\"Magnetisch veld niet gedetecteerd\");\n }\n}\n', '2020-04-03'),
(9, 'IR sensor ', 'Deze module maakt het mogelijk om op een vaste afstand reflectie te registreren. ', 'KY-022', 'img/sensors/ky-022.PNG', 'De werking van de infrarood sensor van een micro-controller werkt als volgt. Een infrarood sensor maakt gebruik van een IR Emitter en een IR receiver. De IR Emitter straalt infrarood licht uit, deze weerkaatst op een object tot een bepaalde afstand en wordt opgevangen door de IR receiver. Hiermee kan je objecten / beweging detecteren.', 'int irPin = 7;  // input pin\nint Obstakel = HIGH;  // HIGH betekent geen detectie\nvoid setup() \n{\n pinMode(irPin, INPUT);\n Serial.begin(9600);\n}\nvoid loop() \n{\n Obstakel = digitalRead(irPin);\n if (Obstakel == LOW)\n {\n   Serial.println(\"Voorwerp gedetecteerd\");  \n }\n else\n {\n   Serial.println(\"clear\");\n }\n delay(200);\n}', '2020-04-03');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `username` varchar(15) NOT NULL,
  `token` varchar(32) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `email` varchar(40) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sensors`
--
ALTER TABLE `sensors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`token`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sensors`
--
ALTER TABLE `sensors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `username` FOREIGN KEY (`username`) REFERENCES `users` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
