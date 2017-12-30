#!/usr/bin/python
# -*- coding: utf-8 -*-
"""
Module: energy.py
Compute the energy used by the pump

@author: ymasur@microclub.ch (YM)

00.12.2017/YM
"""


class Energy:
    p_power = 45.0  # Watts, nominal
    nb_samples = 0
    use_day = []    # in hours

    def __init__(self, datas):
        self.datas = datas
        self.nb_samples = datas.__len__()
        print("nb of datas: %d" % self.nb_samples)
        datas[0].__repr__

    def set_pump_power(self, p_power):
        self.p_power = p_power  # in [W]

    def get_pump_energy(self, day=0, nb=1):
        e = Energy.use_day[day] * Energy.p_power  # hours * power
        return e
        #print("total jour %d, %.0f Wh" % (day, e))

    def pump_use(self, day=0, nb=1):
        use = 0  # total hours of the pump works

        for s in self.datas:
            if s.dd == day:  # for the day reached, compute total time: 100 := 10 minutes (1/6 hour)
                use = use + (s.pump * (10.0 / 60.0) / 100.0)  # gived each 10 min; in [%]

        Energy.use_day.append(use)  # storage for later use, in base class
        # print("total heures %d, %.1f H" % (day, use))
        return use

    def get_e_of_day(self, day):
        if Energy.use_day.__len__() >= day and 0 < day < 32:
            return Energy.use_day[day - 1] * Energy.p_power  # in [Wh]
        else:
            return 0

    def get_e_of_month(self):
        e_month = 0
        if Energy.use_day.__len__() > 0:
            for i in range (1, Energy.use_day.__len__()):
                e_month += Energy.use_day[i] * Energy.p_power
        return e_month  # in [Wh]

    def get_solar_of_month(self):
        t_sol = 0
        if Energy.use_day.__len__() > 0:
            for i in range (1, Energy.use_day.__len__()):
                t_sol += Energy.use_day[i] * 100.0 / 24.0  # in [%]

            return t_sol / Energy.use_day.__len__()  # in [%] of month
        else:
            return 0.0
