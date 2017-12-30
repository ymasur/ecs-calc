#!/usr/bin/python
# -*- coding: utf-8 -*-
"""
Module: temperatures.py
Compute the energy used by the pump

@author: ymasur@microclub.ch (YM)

28.12.2017/YM
"""
from math import log
import esetup

class Tpoints:
    def __init__(self, v0, v6, va, dt=6.0):
        self.v0 = v0  # temperature at 0:00
        self.v6 = v6  # ECS temp at 6:00 (or 5:50...)
        self.va = va  # ambiant temp at 3:00
        self.dt = dt  # delta time, in hours
        self.tau = 0.0
        self.compute_tau()

    def __repr__(self):
        return "v0: %.2f, v6: %.2f, va: %.2f, dt: %.2f, tau: %.3f" % (self.v0, self.v6, self.va, self.dt, self.tau)

    def get_tau(self):
        return self.tau

    def get_tpoint(self):
        return "%.f\t(%.2f; %.2f; %.2f; %.2f)" % (self.tau, self.v0, self.v6, self.va, self.dt)

    def compute_tau(self):
        try:
            self.tau = self.dt / log((self.v0 - self.va) / (self.v6 - self.va))
        except:
            print("ERR: ", self.__repr__())

        return self.tau


class Temp:
    nb_samples = 0
    nb_days = 0
    cst = []    # in hours

    def __init__(self, datas):
        self.v0 = 0.0
        self.va = 0.0
        self.dt = 6.0
        self.t_cst = 0.0
        self.datas = datas
        self.nb_samples = datas.__len__()
        # print("nb of datas: %d" % self.nb_samples)
        self.add_points()

    def __repr__(self):
        return "Jour: %d\t Cst= %f" % (self.day, self.t_cst)

    def get_tau_of_day(self, i):
        if 0 < i <= Temp.cst.__len__():
            return Temp.cst[i-1].get_tau()
        else:
            return 0.0

    def get_tau_of_day_full(self, i):
        if 0 < i <= Temp.cst.__len__():
            return Temp.cst[i-1].get_tpoint()
        else:
            return "0"

    def get_tau_of_month(self):
        tau_mean = 0.0
        for i in range(1, Temp.nb_days + 1):
            tau_mean += self.get_tau_of_day(i)

        if Temp.nb_days > 0:
            tau_mean /= Temp.nb_days
        return tau_mean


    def add_points(self):
        self.v0 = 0.0
        self.v6 = 0.0
        self.va = 20.0
        self.dt = 6.0
        self.day = 1
        v6_min = 0.0

        for s in self.datas:  # s is a sample
            if s.dd == self.day:  # for the day reached, compute
                if s.mm == 0:  # past midnight, first measure
                    self.v0 = s.t_medium
                    continue

                if s.mm == 3 * 60:  # use t_pannel as ambient temperature
                    self.va = s.t_pannel
                    v6_min = s.t_medium
                    continue

                if 5 * 60 < s.mm <= 6 * 60:  # last hour, sample before gaz eating
                    if v6_min >= s.t_medium:  # track the decreasing value
                        v6_min = s.t_medium
                        self.dt = s.mm / 60.0  # and time

                if s.mm == 6 * 60:  # last sample time; compute the Tpoints
                    self.v6 = v6_min
                    self.t_cst = Tpoints(self.v0, self.v6, self.va, self.dt)
                    Temp.cst.append(self.t_cst)  # store as day value
                    self.day = self.day + 1

        Temp.nb_days = self.day - 1


# Unity tests here
if __name__ == '__main__':
    """
    print("Tau, avec 49.8; 47.9 et Va: 19.2: Tau = 93.59")
    tau93 = Tpoints(49.8, 47.9, 19.2)
    print(tau93.__repr__())
    print("Tau, avec 49.8; 49.8 et Va: 19.2: ERR; Tau=0.0")
    tau0 = Tpoints(49.8, 49.8, 19.2)
    print(tau0.__repr__())
    """
    import samples
    global e_datas
    samples.readFile()

    t_calc = Temp(samples.s_array)
    for i in range(0, 31):
       print("Jour: %2d\t%s" % (i+1, t_calc.cst[i]))

