#!/usr/bin/python
# -*- coding: utf-8 -*-
"""
ecs-calc.py
-----------
30.12.2017 - ymasur@microclub.ch
07.01.2018/YM
"""

from __future__ import unicode_literals
import sys
import esetup
import samples, energy, temperatures

__module__ = "ecs-calc"
__author__ = 'Yves Masur'
NAME = "ecs-calc"
VERSION = "Version 1.01 - Yves Masur"

e_datas = 0  # maintained array of datas

def save_report(report, file="tempdata.report" ):
    try:
        fp = open(file,"w")
        fp.write(report)
        fp.close()
        print(u"\nEnregistre sous: %s" % file)
    except IOError:
        so = u"\nFichier %s - impossible d'ecrire!" % file
        print (so)
        sys.exit(-1)


def main(argv):
    global e_datas

    report = "Jour\tFonctionnement Solaire[%]\t cst temps[h]"
    esetup.set_args(argv)
    samples.readFile(esetup.infile)  # read file, and store samples
    e_datas = energy.Energy(samples.Samples.s_array)  # store energy usage
    t_calc = temperatures.Temp(samples.Samples.s_array)  # store temperatures

    for i in range(1, temperatures.Temp.nb_days+1):
        h = e_datas.pump_use(i) * 100.0 / 24.0
        # e = e_datas.get_e_of_day(i)
        tau = t_calc.get_tau_of_day_full(i)
        sample_day = t_calc.get_day(i)
        report += "\n%02d\t%02.1f\t%s" % (sample_day, h, tau)

    report += "\n\nSolaire, pourcentage du mois:\t%2.1f [%%]" % e_datas.get_solar_of_month()
    report += "\nPompe, consommation du mois:\t%.2f [kWh]" % (e_datas.get_e_of_month() / 1000.0)
    report += "\nConstante de temps moyenne:\t%.1f [h]" % t_calc.get_tau_of_month()
    print(report)
    save_report(report, esetup.outfile)
    print("\n")
    return 0


# Starts here
if __name__ == '__main__':
    main(sys.argv)
# end main
