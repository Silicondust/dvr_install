SRCFILE=src/hdhr_wrapper.c
OUTFILE=hdhr_wrapper
OUTFILE_ARM7=$(OUTFILE)_arm7
OUTFILE_ARM8=$(OUTFILE)_arm8
OUTFILE_X86=$(OUTFILE)_i686
OUTFILE_X86_64=$(OUTFILE)_x86_64
QNAP_OUTDIR=bin


#####
# Note - using Qnap specific tools - but should work for Synology and Asustor too.

# Default is where the current QDK toolchain docs request you to install.
# Update this if you need to change it
QNAP_XCC_TOOLS=/opt/cross-project
QNAP_CT_TOOLS=/opt/CT
QNAP_ARM7_GCC=$(QNAP_XCC_TOOLS)/arm/marvell/bin/arm-none-linux-gnueabi-gcc
QNAP_ARM8_GCC=$(QNAP_CT_TOOLS)/arm-Realtek-linux-androideabi/bin/arm-linux-androideabi-gcc
QNAP_X86_GCC=$(QNAP_CT_TOOLS)/i686-QNAP-linux-gnu/cross-tools/bin/i686-QNAP-linux-gnu-gcc
QNAP_X86_64_GCC=$(QNAP_CT_TOOLS)/x86_64-QNAP-linux-gnu/cross-tools/bin/x86_64-QNAP-linux-gnu-gcc


$(QNAP_OUTDIR)/$(OUTFILE_X86):
	$(QNAP_X86_GCC) -o $(QNAP_OUTDIR)/$(OUTFILE_X86) $(SRCFILE)

$(QNAP_OUTDIR)/$(OUTFILE_X86_64):
	$(QNAP_X86_64_GCC) -o $(QNAP_OUTDIR)/$(OUTFILE_X86_64) $(SRCFILE)

$(QNAP_OUTDIR)/$(OUTFILE_ARM7):
	$(QNAP_ARM7_GCC) -o $(QNAP_OUTDIR)/$(OUTFILE_ARM7) $(SRCFILE)

# Placeholder for ARMv8 from QNAP - current toolset not building correctly
$(QNAP_OUTDIR)/$(OUTFILE_ARM8):
	$(QNAP_ARM8_GCC) -o $(QNAP_OUTDIR)/$(OUTFILE_ARM8) $(SRCFILE)
	

qnap_all: $(QNAP_OUTDIR)/$(OUTFILE_ARM7) \
					$(QNAP_OUTDIR)/$(OUTFILE_X86) \
          $(QNAP_OUTDIR)/$(OUTFILE_X86_64)


####
# General Build instructions
#
all: qnap_all
	
clean:
	rm $(QNAP_OUTDIR)/$(OUTFILE_ARM7) $(QNAP_OUTDIR)/$(OUTFILE_ARM8) \
	   $(QNAP_OUTDIR)/$(OUTFILE_X86) $(QNAP_OUTDIR)/$(OUTFILE_X86_64) 
