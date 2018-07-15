#!/usr/bin/python3

from tsl2561 import TSL2561
from bme680 import BME680
from ds18b20_therm import DS18B20
import bme680

tsl = TSL2561()
bme = BME680(i2c_addr=bme680.I2C_ADDR_SECONDARY)
ds = DS18B20()

bme.set_humidity_oversample(bme680.OS_2X)
bme.set_pressure_oversample(bme680.OS_4X)
bme.set_temperature_oversample(bme680.OS_8X)
bme.set_filter(bme680.FILTER_SIZE_3)
bme.set_gas_status(bme680.ENABLE_GAS_MEAS)

bme.set_gas_heater_temperature(320)
bme.set_gas_heater_duration(150)
bme.select_gas_heater_profile(0)

bme.get_sensor_data()

sample = {
    "air_temp": 0,
    "ground_temp": 0,
    "pressure": 0,
    "humidity": 0,
    "air_conductivity": 0,
    "light": 0
    }


sample ["light"]=tsl.lux()
sample ["ground_temp"]=ds.read_temp()
sample ["air_temp"]=bme.data.temperature
sample ["pressure"]=bme.data.pressure
sample ["humidity"]=bme.data.humidity
sample ["air_conductivity"]=bme.data.gas_resistance

print(sample)
