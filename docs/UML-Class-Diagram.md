

\# UML Class Diagram — Parking Management System



```mermaid

classDiagram

&#x20;   direction TB



&#x20;   class ParkingFeeStrategy {

&#x20;       <<interface>>

&#x20;       +calculate(minutes: int) int

&#x20;   }



&#x20;   class AbstractParkingFeeStrategy {

&#x20;       <<abstract>>

&#x20;       -hourlyRate: int

&#x20;       +\_\_construct(hourlyRate: int)

&#x20;       #calculateHourlyFee(minutes: int) int

&#x20;   }



&#x20;   class ParkingFeeCalculator {

&#x20;       +\_\_construct()

&#x20;       +calculate(minutes: int) int

&#x20;   }



&#x20;   class DiscountedParkingFeeCalculator {

&#x20;       +\_\_construct(hourlyRate: int)

&#x20;       +calculate(minutes: int) int

&#x20;   }



&#x20;   ParkingFeeStrategy <|.. AbstractParkingFeeStrategy

&#x20;   AbstractParkingFeeStrategy <|-- ParkingFeeCalculator

&#x20;   AbstractParkingFeeStrategy <|-- DiscountedParkingFeeCalculator

```
